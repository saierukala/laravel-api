<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import { can } from '@/lib/can';
import {
    create,
    destroy as usersDestroy,
    edit as usersEdit,
} from '@/routes/users';
type UserRow = {
    id: number;
    name: string;
    email: string;
    roles: Array<{
        id: number;
        name: string;
    }>;
};

defineProps<{
    users: UserRow[];
}>();

const confirmDeleteUser = () =>
    window.confirm('Are you sure you want to delete this user?');

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Users',
                href: '/users',
            },
        ],
    },
});
</script>

<template>
    <Head title="Users" />

    <div
        class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
    >
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold">Users</h2>

            <Link
                v-if="can('users.create')"
                :href="create()"
                class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
            >
                + Create User
            </Link>
        </div>
        <div
            class="overflow-hidden rounded-xl border border-sidebar-border/70 bg-background dark:border-sidebar-border"
        >
            <table class="w-full text-left text-sm">
                <thead class="bg-muted/40">
                    <tr>
                        <th class="px-4 py-3 font-medium">ID</th>
                        <th class="px-4 py-3 font-medium">Name</th>
                        <th class="px-4 py-3 font-medium">Email</th>
                        <th class="px-4 py-3 font-medium">Roles</th>
                        <th class="px-4 py-3 font-medium">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="user in users"
                        :key="user.id"
                        class="border-t border-sidebar-border/50 dark:border-sidebar-border"
                    >
                        <td class="px-4 py-3">{{ user.id }}</td>
                        <td class="px-4 py-3">{{ user.name }}</td>
                        <td class="px-4 py-3">{{ user.email }}</td>
                        <td class="px-4 py-3">
                            <div
                                v-if="user.roles.length > 0"
                                class="flex flex-wrap gap-2"
                            >
                                <span
                                    v-for="role in user.roles"
                                    :key="role.id"
                                    class="rounded-full bg-muted px-2.5 py-1 text-xs"
                                >
                                    {{ role.name }}
                                </span>
                            </div>

                            <span v-else class="text-muted-foreground">
                                No roles assigned.
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap items-center gap-2">
                                <Link
                                    v-if="can('users.edit')"
                                    :href="usersEdit(user.id)"
                                    class="rounded-md border px-3 py-1 text-xs font-medium"
                                >
                                    Edit
                                </Link>

                                <Form
                                    v-if="can('users.delete')"
                                    v-bind="usersDestroy.form(user.id)"
                                    v-slot="{ processing }"
                                >
                                    <button
                                        type="submit"
                                        class="rounded-md border border-red-300 px-3 py-1 text-xs font-medium text-red-600"
                                        :disabled="processing"
                                        @click="
                                            (event) => {
                                                if (!confirmDeleteUser()) {
                                                    event.preventDefault();
                                                }
                                            }
                                        "
                                    >
                                        Delete
                                    </button>
                                </Form>
                            </div>
                        </td>
                    </tr>
                    <tr
                        v-if="users.length === 0"
                        class="border-t border-sidebar-border/50 dark:border-sidebar-border"
                    >
                        <td
                            colspan="5"
                            class="px-4 py-6 text-center text-muted-foreground"
                        >
                            No users found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
