<x-layouts.app title="Create Role">
    <div class="mb-6">
        <a href="{{ route('roles.index') }}" class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400 mb-2 inline-block">
            <i class="fas fa-arrow-left mr-1"></i> Back to Roles
        </a>
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Create New Role</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Define a new role and grant specific permissions across the application.</p>
    </div>

    @if($errors->any())
        <div class="mb-6 rounded-md bg-red-50 dark:bg-red-900/30 p-4 border border-red-200 dark:border-red-800">
            <div class="flex">
                <div class="flex-shrink-0"><i class="fas fa-exclamation-circle text-red-400"></i></div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800 dark:text-red-300">There were {{ $errors->count() }} errors with your submission</h3>
                    <div class="mt-2 text-sm text-red-700 dark:text-red-400">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('roles.store') }}" method="POST">
        @csrf
        
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700 mb-6">
            <div class="mb-8 max-w-md">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Role Name *</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="e.g. Manager"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">Assign Permissions</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">Tick group headers to auto-select all.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" x-data="permissionsManager({{ \Illuminate\Support\Js::from(old('permissions', [])) }}, {{ \Illuminate\Support\Js::from($permissions->keys()) }})">
                @foreach($permissions as $group => $groupPermissions)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden bg-gray-50 dark:bg-gray-900">
                        <div class="bg-gray-100 dark:bg-gray-800 px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                            <label class="flex items-center space-x-2 cursor-pointer w-full select-none">
                                <input type="checkbox" 
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700"
                                    x-model="groupStates['{{ $group }}']"
                                    @change="toggleGroup('{{ $group }}')">
                                <span class="font-bold text-gray-800 dark:text-gray-200 uppercase text-xs tracking-wider">{{ str_replace('-', ' ', $group) }}</span>
                            </label>
                        </div>
                        <div class="p-4 space-y-3">
                            @foreach($groupPermissions as $permission)
                                @php
                                    $action = explode('.', $permission->name)[1] ?? $permission->name;
                                @endphp
                                <label class="flex items-center space-x-3 cursor-pointer select-none">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                        x-model="selectedPermissions"
                                        @change="checkGroupState('{{ $group }}')"
                                        class="permission-checkbox-{{ $group }} rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700"
                                        {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ ucfirst($action) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 pt-5 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                <a href="{{ route('roles.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 mr-3 transition">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md shadow-sm text-sm font-medium hover:bg-blue-700 transition">Create Role</button>
            </div>
        </div>
    </form>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('permissionsManager', (initialPermissions, groups) => ({
                selectedPermissions: initialPermissions,
                groupStates: {},
                
                init() {
                    groups.forEach(group => {
                        this.groupStates[group] = false;
                        this.checkGroupState(group);
                    });
                },
                
                toggleGroup(group) {
                    const groupCheckboxes = document.querySelectorAll('.permission-checkbox-' + group);
                    const isChecked = this.groupStates[group];
                    
                    groupCheckboxes.forEach(checkbox => {
                        const val = checkbox.value;
                        if (isChecked && !this.selectedPermissions.includes(val)) {
                            this.selectedPermissions.push(val);
                        } else if (!isChecked && this.selectedPermissions.includes(val)) {
                            this.selectedPermissions = this.selectedPermissions.filter(item => item !== val);
                        }
                    });
                },
                
                checkGroupState(group) {
                    const groupCheckboxes = document.querySelectorAll('.permission-checkbox-' + group);
                    if (groupCheckboxes.length === 0) return;
                    
                    let allChecked = true;
                    
                    groupCheckboxes.forEach(checkbox => {
                        if (!this.selectedPermissions.includes(checkbox.value)) {
                            allChecked = false;
                        }
                    });
                    
                    this.groupStates[group] = allChecked;
                }
            }))
        })
    </script>
</x-layouts.app>
