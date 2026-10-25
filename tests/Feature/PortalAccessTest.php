<?php

namespace Tests\Feature;

use App\Models\AcademicResource;
use App\Models\CafeItem;
use App\Models\CafeOrder;
use App\Models\CafeTableReservation;
use App\Models\LibraryBook;
use App\Models\LibraryBorrow;
use App\Models\PortalStaff;
use App\Models\User;
use Database\Seeders\CafeMenuSeeder;
use Database\Seeders\LibraryCatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PortalAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitors_only_get_the_project_landing_page_and_four_guest_sections(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('About BAUST Nexus')
            ->assertSee('academic_building.jpeg')
            ->assertSee('Explore guest mode')
            ->assertSee('Go back')
            ->assertDontSee('Gateway');
        $this->get('/guest')->assertOk()->assertSee('Guest Mode');

        foreach (['/notices', '/transport', '/cafe'] as $guestPath) {
            $this->get($guestPath)->assertOk()->assertSee('Go back');
        }
        $this->get('/cafe')->assertSee('cafeteria.jpeg')->assertSee('from-blue-50');
        $this->get('/library')->assertOk()->assertSee('Go back')->assertDontSee('Add New Book')->assertDontSee('ADD BOOK');

        foreach (['/dashboard', '/resources', '/dress-code', '/calendar', '/queries', '/cgpa', '/profile', '/admin'] as $privatePath) {
            $this->get($privatePath)->assertRedirect('/login');
        }

        $this->get('/guest')->assertSee('Public Notices')->assertSee('Library Info')
            ->assertSee('Bus Schedule')->assertSee('Cafeteria Menu');
    }

    public function test_registration_cannot_assign_privileged_roles(): void
    {
        $this->get('/register')
            ->assertOk()
            ->assertSee('x-model="role"', false)
            ->assertSee(":placeholder=\"role === 'teacher' ? 'teacher@baust.edu.bd' : 'student@baust.edu.bd'\"", false)
            ->assertSee('showPasswordConfirmation', false)
            ->assertSee('Show confirm password')
            ->assertSee('Teacher ID');

        $this->get('/login')
            ->assertOk()
            ->assertSee('Show password')
            ->assertSee(':type="showPassword ? \'text\' : \'password\'"', false)
            ->assertSee('-mt-3 max-w-md pt-0 pb-5 sm:pt-2 sm:pb-8')
            ->assertSee('from-blue-700 via-blue-600 to-blue-500')
            ->assertSee('Staff sign-in portals')
            ->assertSee('Main Admin')
            ->assertSee('Cafe Admin')
            ->assertSee('Library Admin');

        $this->from('/register')->post('/register', [
            'name' => 'New Administrator',
            'email' => 'new-admin@example.test',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'role' => 'admin',
            'department' => 'CSE',
        ])->assertSessionHasErrors('role');

        $this->assertDatabaseMissing('users', ['email' => 'new-admin@example.test']);
    }

    public function test_signup_requires_and_saves_the_id_for_the_selected_role(): void
    {
        $teacherData = [
            'name' => 'New Teacher',
            'email' => 'new-teacher@example.test',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'role' => 'teacher',
            'department' => 'CSE',
        ];

        $this->from('/register')->post('/register', $teacherData)
            ->assertSessionHasErrors('teacher_id');

        $this->post('/register', $teacherData + [
            'teacher_id' => 'FAC-102',
            'level' => 'Level 2',
            'term' => 'Term 2',
        ])
            ->assertRedirect('/login')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email' => 'new-teacher@example.test',
            'role' => 'teacher',
            'teacher_id' => 'FAC-102',
            'student_id' => null,
            'department' => 'CSE',
            'level' => null,
            'term' => null,
        ]);

        $this->from('/register')->post('/register', [
            'name' => 'New Student',
            'email' => 'new-student@example.test',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'role' => 'student',
            'department' => 'EEE',
        ])->assertSessionHasErrors('student_id');
    }

    public function test_teacher_dashboard_and_profile_show_teacher_identity_instead_of_student_identity(): void
    {
        $teacher = $this->createUser('teacher', 'faculty@example.test');
        $teacher->update([
            'teacher_id' => 'FAC-501',
            'designation' => 'Lecturer',
            'level' => 'Level 1',
            'term' => 'Term 1',
        ]);

        $this->actingAs($teacher)->get('/dashboard')
            ->assertOk()
            ->assertSee('Teacher ID: FAC-501')
            ->assertSee('>Teacher<', false)
            ->assertDontSee('badge-student');

        $this->get('/profile')
            ->assertOk()
            ->assertSee('Teacher ID')
            ->assertSee('Gender')
            ->assertSee('Date of birth')
            ->assertSee('Blood group')
            ->assertSee('Emergency contact')
            ->assertSee('Address')
            ->assertSee('FAC-501')
            ->assertDontSee('Student ID')
            ->assertDontSee('Level')
            ->assertDontSee('Term')
            ->assertDontSee('name="level"', false)
            ->assertDontSee('name="term"', false);

        $this->post('/profile/update', [
            'name' => $teacher->name,
            'phone' => $teacher->phone,
            'department' => $teacher->department,
            'level' => 'Level 4',
            'term' => 'Term 2',
            'gender' => 'female',
            'date_of_birth' => '2001-05-17',
            'blood_group' => 'AB+',
            'emergency_contact' => '01712345678',
            'address' => 'Saidpur, Nilphamari',
        ])->assertRedirect('/profile');

        $this->assertDatabaseHas('users', [
            'id' => $teacher->id,
            'level' => null,
            'term' => null,
            'gender' => 'female',
            'blood_group' => 'AB+',
            'emergency_contact' => '01712345678',
            'address' => 'Saidpur, Nilphamari',
        ]);
        $this->assertSame('2001-05-17', $teacher->fresh()->date_of_birth->format('Y-m-d'));
    }

    public function test_guest_accounts_cannot_sign_in_to_private_portal_pages(): void
    {
        $guest = $this->createUser('guest', 'guest@example.test');

        $this->get('/login')
            ->assertOk()
            ->assertSee('for="login-email"', false)
            ->assertSee('autocomplete="username"', false)
            ->assertSee('focus:ring-4', false)
            ->assertDontSee('placeholder=', false);

        $this->from('/login')->post('/login', [
            'email' => $guest->email,
            'password' => 'secret123',
        ])->assertRedirect('/login')->assertSessionHasErrors('email');
        $this->assertGuest();

        $this->actingAs($guest)->get('/dashboard')->assertForbidden();
    }

    public function test_back_button_fallbacks_stay_inside_the_portal_for_signed_in_users(): void
    {
        $student = $this->createUser('student', 'student@example.test');

        $this->actingAs($student)->get('/cafe')
            ->assertOk()
            ->assertSee('data-fallback="'.route('dashboard').'"', false)
            ->assertSee("['/login', '/register', '/admin/login', '/cafe/admin/login', '/library/admin/login']", false);

        $this->get('/dashboard')
            ->assertOk()
            ->assertDontSee('Gateway')
            ->assertSee('data-fallback="'.route('resources.index').'"', false);
    }

    public function test_dedicated_admin_logins_only_accept_the_matching_role_and_redirect_to_its_workspace(): void
    {
        $cafeAdmin = $this->createUser('cafe_admin', 'cafe@example.test');
        $this->get('/cafe/admin/login')->assertOk()->assertSee('Cafe Administrator');
        $this->from('/cafe/admin/login')->post('/cafe/admin/login', [
            'email' => $cafeAdmin->email,
            'password' => 'secret123',
        ])->assertRedirect('/cafe');
        $this->assertAuthenticatedAs($cafeAdmin);

        auth()->logout();

        $libraryAdmin = $this->createUser('library_admin', 'library@example.test');
        $this->from('/cafe/admin/login')->post('/cafe/admin/login', [
            'email' => $libraryAdmin->email,
            'password' => 'secret123',
        ])->assertRedirect('/cafe/admin/login')->assertSessionHasErrors('email');
        $this->assertGuest();

        $this->from('/library/admin/login')->post('/library/admin/login', [
            'email' => $libraryAdmin->email,
            'password' => 'secret123',
        ])->assertRedirect('/library');
        $this->assertAuthenticatedAs($libraryAdmin);

        auth()->logout();

        $admin = $this->createUser('admin', 'main-admin@example.test');
        $pendingStudent = User::create([
            'name' => 'Pending Student Account',
            'email' => 'pending-tab-student@example.test',
            'password' => Hash::make('secret123'),
            'role' => 'student',
            'department' => 'CSE',
            'is_approved' => false,
        ]);
        $this->from('/admin/login')->post('/admin/login', [
            'email' => $admin->email,
            'password' => 'secret123',
        ])->assertRedirect('/admin');
        $this->assertAuthenticatedAs($admin);

        $this->get('/admin')
            ->assertOk()
            ->assertSee('Admin Dashboard')
            ->assertSee('User Accounts')
            ->assertSee('[x-cloak]', false)
            ->assertSee('x-show="editUser" x-cloak', false)
            ->assertSee("x-if=\"editUser.role === 'student'\"", false)
            ->assertSee("x-if=\"editUser.role === 'teacher'\"", false)
            ->assertSee('editUser.roleLabel', false);
        $this->get('/admin?section=pending')
            ->assertOk()
            ->assertSee('Pending Account Approvals')
            ->assertSee('aria-current="page"', false)
            ->assertSee($pendingStudent->name)
            ->assertDontSee($admin->email)
            ->assertDontSee('Manage Campus Features')
            ->assertDontSee('User Accounts &amp; Approvals', false);
        $this->get('/admin?section=features')
            ->assertOk()
            ->assertSee('Manage Campus Features')
            ->assertSee('Manage Library &amp; Books', false)
            ->assertSee('Order from Campus Cafe')
            ->assertSee('Academic Resources')
            ->assertSee('Query Board')
            ->assertSee('CGPA &amp; Progress', false)
            ->assertDontSee('User Accounts &amp; Approvals', false);
        $this->get('/library')
            ->assertOk()
            ->assertSee('Edit Library Info')
            ->assertSee('Opening hours');
    }

    public function test_main_admin_can_edit_user_details_remove_pending_applications_and_keep_admin_access(): void
    {
        $admin = $this->createUser('admin', 'main-admin@example.test');
        $teacher = $this->createUser('teacher', 'editable-teacher@example.test');
        $teacher->update([
            'level' => 'Level 2',
            'term' => 'Term 1',
            'student_id' => 'STALE-STUDENT-ID',
        ]);
        $pending = User::create([
            'name' => 'Pending Student',
            'email' => 'pending-student@example.test',
            'password' => Hash::make('secret123'),
            'role' => 'student',
            'student_id' => 'STU-44',
            'department' => 'CSE',
            'is_approved' => false,
        ]);

        $this->actingAs($admin)->get('/admin?status=pending')
            ->assertOk()
            ->assertSee('Remove pending')
            ->assertSee('Pending Student')
            ->assertDontSee('editable-teacher@example.test');

        $this->put(route('admin.users.update', $teacher), [
            'name' => 'Updated Faculty Name',
            'email' => 'updated-teacher@example.test',
            'teacher_id' => 'FAC-204',
            'student_id' => 'STALE-STUDENT-ID',
            'department' => 'EEE',
            'level' => 'Level 3',
            'term' => 'Term 2',
            'designation' => 'Lecturer',
            'phone' => '01700000000',
        ])->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $teacher->id,
            'name' => 'Updated Faculty Name',
            'email' => 'updated-teacher@example.test',
            'teacher_id' => 'FAC-204',
            'student_id' => null,
            'department' => 'EEE',
            'designation' => 'Lecturer',
            'level' => null,
            'term' => null,
        ]);

        $this->delete(route('admin.users.delete', $pending))
            ->assertRedirect()
            ->assertSessionHas('success');
        $this->assertDatabaseMissing('users', ['id' => $pending->id]);

        $this->post(route('admin.users.role', $admin), ['role' => 'student'])
            ->assertRedirect()
            ->assertSessionHas('error');
        $this->delete(route('admin.users.delete', $admin))
            ->assertRedirect()
            ->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $admin->id, 'role' => 'admin']);
    }

    public function test_role_changes_clear_identity_fields_that_do_not_belong_to_the_new_role(): void
    {
        $admin = $this->createUser('admin', 'role-change-admin@example.test');
        $account = User::create([
            'name' => 'Role Change Account',
            'email' => 'role-change-account@example.test',
            'password' => Hash::make('secret123'),
            'role' => 'admin',
            'student_id' => 'STU-10',
            'teacher_id' => 'FAC-10',
            'level' => 'Level 2',
            'term' => 'Term 1',
            'designation' => 'Lecturer',
            'department' => 'CSE',
            'is_approved' => true,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.users.role', $account), ['role' => 'cafe_admin'])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $account->id,
            'role' => 'cafe_admin',
            'student_id' => null,
            'teacher_id' => null,
            'level' => null,
            'term' => null,
            'designation' => null,
        ]);
    }

    public function test_admin_accounts_can_be_synced_with_unique_environment_credentials(): void
    {
        $credentials = [
            'BAUST_MAIN_ADMIN_PASSWORD' => 'main-secret-123',
            'BAUST_CAFE_ADMIN_PASSWORD' => 'cafe-secret-456',
            'BAUST_LIBRARY_ADMIN_PASSWORD' => 'library-secret-789',
        ];
        $environment = Env::getRepository();
        $originalCredentials = [];

        foreach ($credentials as $name => $value) {
            $originalCredentials[$name] = $environment->get($name);
            $environment->clear($name);
            $environment->set($name, $value);
        }

        try {
            $this->artisan('nexus:sync-admin-accounts')->assertSuccessful();
        } finally {
            foreach ($originalCredentials as $name => $value) {
                $environment->clear($name);
                if ($value !== null) {
                    $environment->set($name, $value);
                }
            }
        }

        foreach ([
            'admin' => ['admin@baust.edu.bd', 'main-secret-123'],
            'cafe_admin' => ['cafeadmin@baust.edu.bd', 'cafe-secret-456'],
            'library_admin' => ['libraryadmin@baust.edu.bd', 'library-secret-789'],
        ] as $role => [$email, $password]) {
            $account = User::where('role', $role)->firstOrFail();
            $this->assertSame($email, $account->email);
            $this->assertTrue(Hash::check($password, $account->password));
        }
    }

    public function test_only_designated_roles_can_manage_their_portals_or_upload_resources(): void
    {
        $student = $this->createUser('student', 'student@example.test');

        $this->actingAs($student)->get('/admin')->assertForbidden();
        $this->post('/cafe/store', [])->assertForbidden();
        $this->post('/library/books/store', [])->assertForbidden();
        $this->post('/resources/store', [])->assertForbidden();
    }

    public function test_cafe_and_library_administrators_manage_only_their_own_catalogs(): void
    {
        $cafeAdmin = $this->createUser('cafe_admin', 'cafe-manager@example.test');
        $this->actingAs($cafeAdmin)->post('/cafe/store', [
            'name' => 'Campus tea',
            'category' => 'beverages',
            'price' => 20,
            'description' => 'Fresh tea',
            'is_available' => 1,
        ])->assertRedirect()->assertSessionHas('success');
        $this->assertDatabaseHas('cafe_items', ['name' => 'Campus tea']);
        $cafeItem = CafeItem::where('name', 'Campus tea')->firstOrFail();
        $this->get('/cafe?tab=inventory')->assertOk()->assertSee('Campus tea')->assertSee('Inventory Management');
        $this->post(route('cafe.staff.store'), [
            'name' => 'Cafe Staff Test',
            'position' => 'Cashier',
            'phone' => '01712345678',
            'duty_hours' => '8:00 AM - 4:00 PM',
        ])->assertRedirect()->assertSessionHas('success');
        $this->assertDatabaseHas('portal_staff', [
            'unit' => 'cafe',
            'name' => 'Cafe Staff Test',
            'duty_hours' => '8:00 AM - 4:00 PM',
        ]);
        $cafeStaffId = PortalStaff::where('unit', 'cafe')->value('id');
        $this->get('/cafe?tab=staff')->assertOk()->assertSee('Cafe Staff Test');
        $this->delete(route('cafe.items.destroy', $cafeItem))->assertRedirect()->assertSessionHas('success');
        $this->assertDatabaseMissing('cafe_items', ['id' => $cafeItem->id]);
        $this->delete(route('cafe.staff.destroy', $cafeStaffId))->assertRedirect()->assertSessionHas('success');
        $this->assertDatabaseMissing('portal_staff', ['id' => $cafeStaffId]);
        $this->get('/queries')->assertOk();
        $this->post('/library/books/store', [])->assertForbidden();

        auth()->logout();
        $libraryAdmin = $this->createUser('library_admin', 'library-manager@example.test');
        $this->actingAs($libraryAdmin)->get('/library')->assertOk()->assertSee('Add New Book');
        $this->actingAs($libraryAdmin)->post('/library/books/store', [
            'title' => 'Campus handbook',
            'author' => 'BAUST',
            'category' => 'Literature',
            'department' => 'All',
            'total_copies' => 3,
            'shelf_location' => 'Rack B-1',
        ])->assertRedirect()->assertSessionHas('success');
        $this->assertDatabaseHas('library_books', ['title' => 'Campus handbook']);
        $this->post(route('library.staff.store'), [
            'name' => 'Library Staff Test',
            'position' => 'Librarian',
            'duty_hours' => '9:00 AM - 5:00 PM',
        ])->assertRedirect()->assertSessionHas('success');
        $this->assertDatabaseHas('portal_staff', [
            'unit' => 'library',
            'name' => 'Library Staff Test',
            'duty_hours' => '9:00 AM - 5:00 PM',
        ]);
        $this->get('/library?tab=manage')->assertOk()
            ->assertSee('Library Staff Management')
            ->assertSee('Library Staff Test')
            ->assertSee('Remove book')
            ->assertSee('Save book changes');
        $libraryStaffId = PortalStaff::where('unit', 'library')->value('id');
        $this->delete(route('library.staff.destroy', $libraryStaffId))
            ->assertRedirect()
            ->assertSessionHas('success');
        $this->assertDatabaseMissing('portal_staff', ['id' => $libraryStaffId]);
        $this->post('/cafe/store', [])->assertForbidden();

        auth()->logout();
        $student = $this->createUser('student', 'library-student@example.test');
        $this->actingAs($student)->get('/library')->assertOk()->assertDontSee('Add New Book');
    }

    public function test_cafe_admin_is_limited_to_cafe_and_library_admin_can_manage_library_hours_and_queries(): void
    {
        $cafeAdmin = $this->createUser('cafe_admin', 'scoped-cafe@example.test');
        $this->actingAs($cafeAdmin)->get('/dashboard')->assertRedirect('/cafe');
        $this->get('/cafe')
            ->assertOk()
            ->assertSee('Cafe Management')
            ->assertSee('Queries')
            ->assertDontSee('Library Management');

        foreach (['/resources', '/cgpa', '/dress-code', '/calendar'] as $restrictedPath) {
            $this->get($restrictedPath)->assertForbidden();
        }
        $this->get('/queries')->assertOk();
        $this->get('/profile')->assertOk();
        $this->get('/admin')->assertForbidden();

        auth()->logout();
        $libraryAdmin = $this->createUser('library_admin', 'scoped-library@example.test');
        $this->actingAs($libraryAdmin)->get('/dashboard')->assertRedirect('/library');
        $this->get('/library')
            ->assertOk()
            ->assertSee('Library Management')
            ->assertSee('Edit Library Info')
            ->assertSee('Opening hours')
            ->assertSee('Manage')
            ->assertSee('Queries');
        $this->get('/queries')->assertOk();

        foreach (['/resources', '/cgpa', '/dress-code', '/calendar'] as $restrictedPath) {
            $this->get($restrictedPath)->assertForbidden();
        }

        $this->put(route('library.settings.update'), [
            'opening_hours' => '9:00 AM - 6:00 PM',
            'location' => 'Updated Central Library',
        ])->assertRedirect()->assertSessionHas('success');
        $this->assertDatabaseHas('library_settings', [
            'id' => 1,
            'opening_hours' => '9:00 AM - 6:00 PM',
            'location' => 'Updated Central Library',
        ]);
    }

    public function test_main_admin_can_order_at_cafe_but_cannot_manage_cafe_menu_or_order_status(): void
    {
        $admin = $this->createUser('admin', 'cafe-customer-admin@example.test');
        $item = CafeItem::create([
            'name' => 'Admin cafe order tea',
            'category' => 'beverages',
            'price' => 20,
            'is_available' => true,
        ]);

        $this->actingAs($admin)->get('/cafe')
            ->assertOk()
            ->assertDontSee('Add Item')
            ->assertDontSee('Update status');

        $response = $this->postJson('/cafe/orders', [
            'items' => [['id' => $item->id, 'quantity' => 1]],
            'delivery_option' => 'takeaway',
            'payment_method' => 'cash',
        ])->assertCreated();
        $order = CafeOrder::where('order_token', $response->json('orderToken'))->firstOrFail();

        $this->get('/cafe')
            ->assertOk()
            ->assertSee('Your Cafe Orders')
            ->assertSee($order->order_token)
            ->assertDontSee('Update status');
        $this->post('/cafe/store', [])->assertForbidden();
        $this->post(route('cafe.toggle', $item))->assertForbidden();
        $this->post(route('cafe.orders.status', $order), ['status' => 'preparing'])->assertForbidden();
    }

    public function test_cafe_menu_is_rendered_from_database_and_guest_orders_receive_saved_tokens(): void
    {
        $item = CafeItem::create([
            'name' => 'Fresh milk tea',
            'category' => 'beverages',
            'price' => 20,
            'is_available' => true,
        ]);

        $this->get('/cafe')
            ->assertOk()
            ->assertSee('alpinejs')
            ->assertSee('x-cloak x-show="showOrderModal"', false)
            ->assertSee('aria-labelledby="cafe-order-success-title"', false)
            ->assertSee('@keydown.escape.window="showOrderModal = false"', false)
            ->assertSee('Fresh milk tea')
            ->assertSee('01748608369')
            ->assertSee('Send money to this BAUST Cafe number')
            ->assertSee('Transaction ID');

        $this->postJson('/cafe/orders', [
            'items' => [['id' => $item->id, 'quantity' => 2]],
            'delivery_option' => 'dine-in',
            'floor' => 'Ground Floor',
            'table_number' => 'Table 1',
            'payment_method' => 'cash',
        ])->assertCreated()
            ->assertJsonPath('total', 46)
            ->assertJsonPath('items.0.name', 'Fresh milk tea')
            ->assertJsonPath('items.0.qty', 2);

        $order = CafeOrder::with('items')->firstOrFail();
        $this->assertStringStartsWith('CAF', $order->order_token);
        $this->assertStringStartsWith($order->order_token.'-', $order->items->first()->item_token);
        $this->assertNull($order->user_id);
        $this->assertNotEmpty($order->guest_session_id);
        $this->assertDatabaseHas('cafe_orders', [
            'id' => $order->id,
            'guest_session_id' => $order->guest_session_id,
        ]);
    }

    public function test_cafe_admin_sees_income_and_top_selling_items_excluding_cancelled_orders(): void
    {
        $popularItem = CafeItem::create([
            'name' => 'Popular cafe tea',
            'category' => 'beverages',
            'price' => 100,
            'is_available' => true,
        ]);
        $secondItem = CafeItem::create([
            'name' => 'Campus sandwich',
            'category' => 'snacks',
            'price' => 50,
            'is_available' => true,
        ]);

        $activeOrder = $this->postJson('/cafe/orders', [
            'items' => [
                ['id' => $popularItem->id, 'quantity' => 2],
                ['id' => $secondItem->id, 'quantity' => 1],
            ],
            'delivery_option' => 'takeaway',
            'payment_method' => 'cash',
        ])->assertCreated();
        $cancelledOrder = $this->postJson('/cafe/orders', [
            'items' => [['id' => $popularItem->id, 'quantity' => 9]],
            'delivery_option' => 'takeaway',
            'payment_method' => 'cash',
        ])->assertCreated();

        CafeOrder::where('order_token', $cancelledOrder->json('orderToken'))
            ->update(['status' => 'cancelled']);

        $cafeAdmin = $this->createUser('cafe_admin', 'sales-dashboard@example.test');
        $this->actingAs($cafeAdmin)->get('/cafe')
            ->assertOk()
            ->assertSee('Cafe business snapshot')
            ->assertSee('Total cafe income')
            ->assertSee('৳287.50')
            ->assertSee('Today')
            ->assertSee('income')
            ->assertSee('>3</p>', false)
            ->assertSee('Popular cafe tea')
            ->assertSee('2 sold')
            ->assertDontSee('9 sold');

        $this->assertDatabaseHas('cafe_orders', [
            'order_token' => $activeOrder->json('orderToken'),
            'status' => 'pending',
        ]);
    }

    public function test_cafe_seeder_adds_the_requested_campus_menu_and_weekly_schedule(): void
    {
        $this->seed(CafeMenuSeeder::class);

        $response = $this->get('/cafe')->assertOk();

        $this->assertSame(17, CafeItem::where('is_special_offer', false)->count());
        $this->assertSame(22, CafeItem::where('is_special_offer', true)->count());
        $this->assertSame(10.0, (float) CafeItem::where('name', 'Singara')->value('price'));
        $this->assertSame(90.0, (float) CafeItem::where('name', 'Pizza')->value('price'));
        $this->assertSame(50.0, (float) CafeItem::where('name', 'Burger')->value('price'));
        $response->assertViewHas('weeklyMenu', function ($days): bool {
            return $days->count() === 7
                && $days->first()['day'] === 'Sunday'
                && $days->last()['day'] === 'Saturday'
                && count($days->first()['meals']) === 4
                && count($days->get(2)['meals'][2]['products']) === 3
                && $days->last()['special'] === true;
        });
        $response->assertViewHas('menuCategories', fn ($categories) => count($categories) === 2);
    }

    public function test_cafe_tables_are_reserved_for_thirty_minutes_and_available_again_after_expiry(): void
    {
        $item = CafeItem::create([
            'name' => 'Reservation test tea',
            'category' => 'beverages',
            'price' => 10,
            'is_available' => true,
        ]);
        $payload = [
            'items' => [['id' => $item->id, 'quantity' => 1]],
            'delivery_option' => 'dine-in',
            'floor' => 'Ground Floor',
            'table_number' => 'Table 1',
            'payment_method' => 'cash',
        ];

        $this->postJson('/cafe/orders', $payload)->assertCreated();

        $reservation = CafeTableReservation::where('floor', 'Ground Floor')
            ->where('table_number', 'Table 1')
            ->firstOrFail();
        $this->assertTrue($reservation->expires_at->gt(now()->addMinutes(29)));
        $this->assertTrue($reservation->expires_at->lte(now()->addMinutes(30)));
        $this->getJson('/cafe/tables/availability?floor=Ground%20Floor')
            ->assertOk()
            ->assertJsonFragment(['Table 1']);

        $this->postJson('/cafe/orders', $payload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('table_number');

        $this->travel(31)->minutes();
        $this->postJson('/cafe/orders', $payload)->assertCreated();
    }

    public function test_mobile_wallet_orders_require_and_save_the_payer_number_and_transaction_id(): void
    {
        $item = CafeItem::create([
            'name' => 'Mobile payment tea',
            'category' => 'beverages',
            'price' => 20,
            'is_available' => true,
        ]);
        $payload = [
            'items' => [['id' => $item->id, 'quantity' => 1]],
            'delivery_option' => 'takeaway',
            'payment_method' => 'bkash',
        ];

        $this->postJson('/cafe/orders', $payload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['payment_phone', 'payment_transaction_id']);

        $this->postJson('/cafe/orders', $payload + [
            'payment_phone' => '01234567890',
            'payment_transaction_id' => 'TXN-INVALID-NUMBER',
        ])->assertUnprocessable()->assertJsonValidationErrors('payment_phone');

        $this->postJson('/cafe/orders', $payload + [
            'payment_phone' => '01712345678',
            'payment_transaction_id' => 'BKASH-TXN-1024',
        ])->assertCreated();

        $this->assertDatabaseHas('cafe_orders', [
            'payment_method' => 'bkash',
            'payment_phone' => '01712345678',
            'payment_transaction_id' => 'BKASH-TXN-1024',
        ]);
        $cafeAdmin = $this->createUser('cafe_admin', 'wallet-review@example.test');
        $this->actingAs($cafeAdmin)->get('/cafe')
            ->assertSee('From 01712345678')
            ->assertSee('BKASH-TXN-1024');

        $nagadPayload = [
            ...$payload,
            'payment_method' => 'nagad',
            'payment_phone' => '01812345678',
            'payment_transaction_id' => 'NAGAD-TXN-2026',
        ];
        $this->postJson('/cafe/orders', $nagadPayload)->assertCreated();
        $this->assertDatabaseHas('cafe_orders', [
            'payment_method' => 'nagad',
            'payment_phone' => '01812345678',
            'payment_transaction_id' => 'NAGAD-TXN-2026',
        ]);
    }

    public function test_cancelling_a_dine_in_order_releases_its_table_immediately(): void
    {
        $item = CafeItem::create([
            'name' => 'Cancellation test tea',
            'category' => 'beverages',
            'price' => 10,
            'is_available' => true,
        ]);
        $response = $this->postJson('/cafe/orders', [
            'items' => [['id' => $item->id, 'quantity' => 1]],
            'delivery_option' => 'dine-in',
            'floor' => 'Outdoor Seating',
            'table_number' => 'Table 2',
            'payment_method' => 'cash',
        ])->assertCreated();

        $cafeAdmin = $this->createUser('cafe_admin', 'table-release@example.test');
        $order = CafeOrder::where('order_token', $response->json('orderToken'))->firstOrFail();
        $this->from('/cafe?tab=staff')->actingAs($cafeAdmin)->post(route('cafe.orders.status', $order), [
            'status' => 'cancelled',
        ])->assertRedirect(route('cafe.index', ['tab' => 'orders']))
            ->assertSessionHas('success');

        $reservation = CafeTableReservation::where('floor', 'Outdoor Seating')
            ->where('table_number', 'Table 2')
            ->firstOrFail();
        $this->assertTrue($reservation->expires_at->isPast());
    }

    public function test_library_catalog_seeder_adds_thirty_five_books(): void
    {
        $this->seed(LibraryCatalogSeeder::class);

        $this->assertSame(35, LibraryBook::count());
        $this->assertDatabaseHas('library_books', [
            'title' => 'Database System Concepts',
            'department' => 'CSE',
        ]);
    }

    public function test_main_admin_can_edit_library_info_and_catalog_without_losing_borrowed_copy_counts(): void
    {
        $admin = $this->createUser('admin', 'library-edit-admin@example.test');
        $student = $this->createUser('student', 'library-edit-student@example.test');
        $book = LibraryBook::create([
            'title' => 'Original library title',
            'author' => 'Original author',
            'category' => 'Computer Science',
            'department' => 'CSE',
            'total_copies' => 3,
            'available_copies' => 1,
            'shelf_location' => 'Rack A-1',
        ]);
        LibraryBorrow::create([
            'user_id' => $student->id,
            'library_book_id' => $book->id,
            'token_number' => 'LIB-2026-1001',
            'status' => 'borrowed',
            'issue_date' => now()->toDateString(),
            'return_due_date' => now()->addDays(LibraryBorrow::LOAN_PERIOD_DAYS)->toDateString(),
        ]);

        $this->actingAs($admin)->put(route('library.settings.update'), [
            'opening_hours' => '9:00 AM - 6:00 PM',
            'location' => 'Central Library, First Floor',
        ])->assertRedirect()->assertSessionHas('success');

        $this->actingAs($admin)->put(route('library.books.update', $book), [
            'title' => 'Updated library title',
            'author' => 'Updated author',
            'isbn' => '978-0000000000',
            'category' => 'Literature',
            'department' => 'English',
            'total_copies' => 4,
            'shelf_location' => 'Rack B-2',
            'description' => 'Updated catalog description.',
        ])->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseHas('library_books', [
            'id' => $book->id,
            'title' => 'Updated library title',
            'total_copies' => 4,
            'available_copies' => 2,
            'shelf_location' => 'Rack B-2',
        ]);
        $this->assertDatabaseHas('library_settings', [
            'id' => 1,
            'opening_hours' => '9:00 AM - 6:00 PM',
            'location' => 'Central Library, First Floor',
        ]);

        $this->put(route('library.books.update', $book), [
            'title' => 'Updated library title',
            'author' => 'Updated author',
            'isbn' => '978-0000000000',
            'category' => 'Literature',
            'department' => 'English',
            'total_copies' => 1,
            'shelf_location' => 'Rack B-2',
            'description' => 'Updated catalog description.',
        ])->assertSessionHasErrors('total_copies');
    }

    public function test_library_catalog_editing_and_deletion_are_admin_only_and_preserve_borrow_history(): void
    {
        $student = $this->createUser('student', 'library-protection-student@example.test');
        $book = LibraryBook::create([
            'title' => 'Protected library title',
            'author' => 'Library author',
            'category' => 'Computer Science',
            'department' => 'CSE',
            'total_copies' => 1,
            'available_copies' => 1,
            'shelf_location' => 'Rack C-1',
        ]);

        $this->actingAs($student)
            ->put(route('library.settings.update'), [
                'opening_hours' => '9:00 AM - 6:00 PM',
                'location' => 'Central Library',
            ])->assertForbidden();
        $this->put(route('library.books.update', $book), [])->assertForbidden();
        $this->delete(route('library.books.destroy', $book))->assertForbidden();

        $libraryAdmin = $this->createUser('library_admin', 'library-protection-admin@example.test');
        LibraryBorrow::create([
            'user_id' => $student->id,
            'library_book_id' => $book->id,
            'token_number' => 'LIB-2026-1002',
            'status' => 'returned',
            'issue_date' => now()->subDays(20)->toDateString(),
            'return_due_date' => now()->subDays(6)->toDateString(),
            'returned_at' => now(),
        ]);

        $this->actingAs($libraryAdmin)
            ->delete(route('library.books.destroy', $book))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('library_books', ['id' => $book->id]);
        $this->assertDatabaseHas('library_borrows', ['token_number' => 'LIB-2026-1002']);
    }

    public function test_signed_in_customers_can_view_their_persisted_cafe_orders(): void
    {
        $user = $this->createUser('student', 'cafe-customer@example.test');
        $item = CafeItem::create([
            'name' => 'Campus coffee',
            'category' => 'beverages',
            'price' => 35,
            'is_available' => true,
        ]);

        $response = $this->actingAs($user)->postJson('/cafe/orders', [
            'items' => [['id' => $item->id, 'quantity' => 1]],
            'delivery_option' => 'takeaway',
            'payment_method' => 'cash',
        ])->assertCreated();

        $this->get('/cafe?tab=orders')
            ->assertOk()
            ->assertSee($response->json('orderToken'));
    }

    public function test_teachers_can_request_a_book_from_the_library_book_list(): void
    {
        $teacher = $this->createUser('teacher', 'library-teacher@example.test');
        $book = LibraryBook::create([
            'title' => 'Web Engineering',
            'author' => 'BAUST Library',
            'category' => 'Computer Science',
            'department' => 'CSE',
            'total_copies' => 2,
            'available_copies' => 2,
            'shelf_location' => 'Rack A-1',
        ]);

        $this->actingAs($teacher)->post(route('library.borrow', $book))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('library_borrows', [
            'user_id' => $teacher->id,
            'library_book_id' => $book->id,
            'status' => 'pending',
        ]);
        $this->assertSame(1, $book->fresh()->available_copies);
        $this->assertStringStartsWith('LIB-'.date('Y').'-', LibraryBorrow::firstOrFail()->token_number);
    }

    public function test_library_loan_limits_are_three_for_students_five_for_teachers_and_late_fine_is_five_per_day(): void
    {
        $this->travelTo(now()->setDate(2026, 10, 13)->startOfDay());

        $student = $this->createUser('student', 'three-book-student@example.test');
        $teacher = $this->createUser('teacher', 'five-book-teacher@example.test');
        $books = collect(range(1, 10))->map(fn (int $number) => LibraryBook::create([
            'title' => "Loan policy book {$number}",
            'author' => 'BAUST Library',
            'category' => 'Computer Science',
            'department' => 'CSE',
            'total_copies' => 1,
            'available_copies' => 1,
            'shelf_location' => "Rack {$number}",
        ]));

        foreach ($books->take(3) as $book) {
            $this->actingAs($student)->post(route('library.borrow', $book))
                ->assertRedirect()
                ->assertSessionHas('success');
        }
        $this->actingAs($student)->post(route('library.borrow', $books[3]))
            ->assertRedirect()
            ->assertSessionHas('error', 'You can borrow up to 3 books at a time.');

        foreach ($books->slice(4, 5) as $book) {
            $this->actingAs($teacher)->post(route('library.borrow', $book))
                ->assertRedirect()
                ->assertSessionHas('success');
        }
        $this->actingAs($teacher)->post(route('library.borrow', $books[9]))
            ->assertRedirect()
            ->assertSessionHas('error', 'You can borrow up to 5 books at a time.');

        $studentLoan = LibraryBorrow::where('user_id', $student->id)->firstOrFail();
        $this->assertSame('2026-11-12', $studentLoan->return_due_date);
        $this->travelTo(now()->setDate(2026, 11, 12)->startOfDay());
        $this->assertSame(0, $studentLoan->overdueDays());
        $this->assertSame(0, $studentLoan->fineAmount());

        $this->travelTo(now()->setDate(2026, 11, 13)->startOfDay());
        $this->assertSame(1, $studentLoan->overdueDays());
        $this->assertSame(5, $studentLoan->fineAmount());

        $this->travelTo(now()->setDate(2026, 12, 12)->startOfDay());
        $this->assertSame(30, $studentLoan->overdueDays());
        $this->assertSame(150, $studentLoan->fineAmount());

        $studentLoan->update([
            'status' => 'returned',
            'returned_at' => now(),
        ]);
        $this->assertSame(150, $studentLoan->fresh()->fineAmount());
        $this->actingAs($student)->get('/library?tab=records')
            ->assertOk()
            ->assertSee('30 overdue days')
            ->assertSee('Fine ৳150');
    }

    public function test_cafe_orders_use_current_server_price_and_reject_unavailable_items(): void
    {
        $item = CafeItem::create([
            'name' => 'Discounted sandwich',
            'category' => 'snacks',
            'price' => 100,
            'discount_price' => 80,
            'is_available' => true,
        ]);

        $this->postJson('/cafe/orders', [
            'items' => [['id' => $item->id, 'quantity' => 1]],
            'delivery_option' => 'takeaway',
            'payment_method' => 'cash',
            'coupon_code' => 'SAVE10',
        ])->assertCreated()->assertJsonPath('total', 82.80);

        $item->update(['is_available' => false]);

        $this->postJson('/cafe/orders', [
            'items' => [['id' => $item->id, 'quantity' => 1]],
            'delivery_option' => 'takeaway',
            'payment_method' => 'cash',
        ])->assertUnprocessable()->assertJsonValidationErrors('items');
    }

    public function test_each_signed_in_user_can_view_their_account_and_upload_a_profile_picture(): void
    {
        Storage::fake('public');
        $user = $this->createUser('student', 'profile@example.test');

        $this->actingAs($user)
            ->get('/profile')
            ->assertOk()
            ->assertSee('My account')
            ->assertSee('Student ID')
            ->assertSee($user->email);

        $this->post('/profile/update', [
            'name' => $user->name,
            'phone' => $user->phone,
            'department' => $user->department,
            'level' => $user->level,
            'term' => $user->term,
            'gender' => 'male',
            'date_of_birth' => '2002-06-15',
            'blood_group' => 'B+',
            'emergency_contact' => '01711112222',
            'address' => 'Saidpur',
            'avatar' => UploadedFile::fake()->createWithContent(
                'profile.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+j1ZsAAAAASUVORK5CYII=')
            ),
        ])->assertRedirect('/profile')->assertSessionHas('success');

        $avatar = $user->fresh()->avatar;
        $this->assertNotEmpty($avatar);
        Storage::disk('public')->assertExists($avatar);
        $this->assertSame('/storage/'.$avatar, Storage::disk('public')->url($avatar));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'gender' => 'male',
            'blood_group' => 'B+',
            'emergency_contact' => '01711112222',
            'address' => 'Saidpur',
        ]);
        $this->assertSame('2002-06-15', $user->fresh()->date_of_birth->format('Y-m-d'));

        $this->get('/profile')
            ->assertOk()
            ->assertSee(Storage::disk('public')->url($avatar))
            ->assertSee('15 Jun, 2002')
            ->assertSee($user->fresh()->date_of_birth->age.' years')
            ->assertSee('Blood group')
            ->assertSee('B+');
    }

    public function test_teachers_and_admins_can_manage_resources_while_students_only_download_them(): void
    {
        Storage::fake('local');
        Storage::fake('public');
        $teacher = $this->createUser('teacher', 'teacher@example.test');

        $this->actingAs($teacher)->get('/resources')
            ->assertOk()
            ->assertSee('type="file" name="file" required', false)
            ->assertSee('Browse and select a resource')
            ->assertDontSee('External Resource Link');

        $resourceDetails = [
            'title' => 'Week one notes',
            'resource_type' => 'lecture_note',
            'department' => 'CSE',
            'level' => 'Level 3',
            'term' => 'Term 2',
            'course_code' => 'CSE-3201',
            'course_title' => 'Web Engineering',
        ];

        $this->post('/resources/store', $resourceDetails)
            ->assertSessionHasErrors('file');

        $this->actingAs($teacher)->post('/resources/store', [
            ...$resourceDetails,
            'file' => UploadedFile::fake()->create('week-one.pdf', 12, 'application/pdf'),
        ])->assertRedirect()->assertSessionHas('success');

        $resource = AcademicResource::firstOrFail();
        Storage::disk('local')->assertExists($resource->file_path);
        Storage::disk('public')->assertMissing($resource->file_path);
        $this->get('/resources')
            ->assertOk()
            ->assertSee('title="Edit resource"', false)
            ->assertSee('title="Delete resource"', false);

        $oldFilePath = $resource->file_path;
        $this->put(route('resources.update', $resource), [
            ...$resourceDetails,
            'description' => 'Updated course notes',
            'file' => UploadedFile::fake()->create('week-one-updated.pdf', 12, 'application/pdf'),
        ])->assertRedirect()->assertSessionHas('success');
        $resource = $resource->fresh();
        $this->assertDatabaseHas('academic_resources', [
            'id' => $resource->id,
            'description' => 'Updated course notes',
        ]);
        Storage::disk('local')->assertMissing($oldFilePath);
        Storage::disk('local')->assertExists($resource->file_path);

        $student = $this->createUser('student', 'download@example.test');
        $this->actingAs($student)->get('/resources')
            ->assertOk()
            ->assertSee('Download Resource')
            ->assertDontSee('title="Edit resource"', false)
            ->assertDontSee('title="Delete resource"', false)
            ->assertDontSee('uploadModal');
        $this->post('/resources/store', [])->assertForbidden();
        $this->put(route('resources.update', $resource), [])->assertForbidden();
        $this->delete(route('resources.destroy', $resource))->assertForbidden();

        $this->actingAs($student)->get(route('resources.download', $resource))->assertDownload('week-one-notes.pdf');
        $this->assertSame(1, $resource->fresh()->downloads_count);

        $admin = $this->createUser('admin', 'resource-admin@example.test');
        $this->actingAs($admin)->get('/resources')
            ->assertOk()
            ->assertSee('type="file" name="file" required', false)
            ->assertSee('title="Edit resource"', false)
            ->assertSee('title="Delete resource"', false);

        $this->post('/resources/store', [
            ...$resourceDetails,
            'title' => 'Admin-uploaded notes',
            'file' => UploadedFile::fake()->create('admin-notes.pdf', 12, 'application/pdf'),
        ])->assertRedirect()->assertSessionHas('success');

        $adminResource = AcademicResource::where('title', 'Admin-uploaded notes')->firstOrFail();
        Storage::disk('local')->assertExists($adminResource->file_path);
        $this->delete(route('resources.destroy', $adminResource))
            ->assertRedirect()
            ->assertSessionHas('success');
        $this->assertDatabaseMissing('academic_resources', ['id' => $adminResource->id]);
        Storage::disk('local')->assertMissing($adminResource->file_path);
    }

    private function createUser(string $role, string $email): User
    {
        return User::create([
            'name' => ucfirst(str_replace('_', ' ', $role)),
            'email' => $email,
            'password' => Hash::make('secret123'),
            'role' => $role,
            'department' => 'CSE',
            'is_approved' => true,
        ]);
    }
}
