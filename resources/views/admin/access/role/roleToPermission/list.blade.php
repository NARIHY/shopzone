<x-layouts.app :title="__('Assign permissions to :role', ['role' => $role->roleName])">

    <div class="flex flex-col space-y-4 p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md transition-colors duration-200">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
            {{ __('Role permissions:') }}
            <span class="text-indigo-600 dark:text-indigo-400">{{ $role->roleName }}</span>
        </h1>

        <form action="{{ route('admin.roleToPermission.update', $role->id) }}" method="POST">
            @csrf

            <table class="min-w-full border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-200">{{ __('Permission') }}</th>
                        <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-200">{{ __('Description') }}</th>
                        <th class="px-4 py-2 text-center text-gray-700 dark:text-gray-200">{{ __('Assigned') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800">
                    @foreach($allPermissions as $permission)
                        <tr class="border-t border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-4 py-2 text-gray-800 dark:text-gray-100">{{ $permission->name }}</td>
                            <td class="px-4 py-2 text-gray-600 dark:text-gray-300">{{ $permission->description }}</td>
                            <td class="px-4 py-2 text-center">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                    {{ in_array($permission->id, $assignedPermissions) ? 'checked' : '' }}
                                    class="w-5 h-5 accent-indigo-600 dark:accent-indigo-500" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            {{-- <div class="mt-4">
                {{ $permissions->links() }} <!-- Affiche les liens de pagination -->
            </div> --}}

            <div class="flex justify-end mt-4">
                <button type="submit"
                    class="bg-indigo-600 dark:bg-indigo-500 text-white px-6 py-2 rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600 transition">
                    {{ __('Update') }}
                </button>
            </div>
        </form>
    </div>

</x-layouts.app>
