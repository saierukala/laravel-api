<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import { can } from '@/lib/can';
import {
    create,
    destroy as rolesDestroy,
    edit as rolesEdit,
} from '@/routes/roles';

type RoleRow = {
    id: number;
    name: string;
    permissions: string[];
};

defineProps<{
    roles: RoleRow[];
}>();

const confirmDeleteRole = () =>
    window.confirm('Are you sure you want to delete this role?');

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Roles',
                href: '/roles',
            },
        ],
    },
});
</script>

<template>
    <Head title="Roles" />

    <div
        class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
    >
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold">Roles</h2>

            <Link
                v-if="can('roles.create')"
                :href="create()"
                class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
            >
                + Create Role
            </Link>
        </div>

        <div
            class="overflow-hidden rounded-xl border border-sidebar-border/70 bg-background dark:border-sidebar-border"
        >
            <table class="w-full text-left text-sm">
                <thead class="bg-muted/40">
                    <tr>
                        <th class="px-4 py-3 font-medium">Role Name</th>
                        <th class="px-4 py-3 font-medium">Permissions</th>
                        <th class="px-4 py-3 font-medium">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="role in roles"
                        :key="role.id"
                        class="border-t border-sidebar-border/50 dark:border-sidebar-border"
                    >
                        <td class="px-4 py-3 font-medium">{{ role.name }}</td>
                        <td class="px-4 py-3">
                            <div
                                v-if="role.permissions.length > 0"
                                class="flex flex-wrap gap-2"
                            >
                                <span
                                    v-for="permission in role.permissions"
                                    :key="permission"
                                    class="rounded-full bg-muted px-2.5 py-1 text-xs"
                                >
                                    {{ permission }}
                                </span>
                            </div>

                            <span v-else class="text-muted-foreground">
                                No permissions assigned.
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap items-center gap-2">
                                <Link
                                    v-if="can('roles.edit')"
                                    :href="rolesEdit(role.id)"
                                    class="rounded-md border px-3 py-1 text-xs font-medium"
                                >
                                    Edit
                                </Link>

                                <Form
                                    v-if="can('roles.delete')"
                                    v-bind="rolesDestroy.form(role.id)"
                                    v-slot="{ processing }"
                                >
                                    <button
                                        type="submit"
                                        class="rounded-md border border-red-300 px-3 py-1 text-xs font-medium text-red-600"
                                        :disabled="processing"
                                        @click="
                                            (event) => {
                                                if (!confirmDeleteRole()) {
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
                        v-if="roles.length === 0"
                        class="border-t border-sidebar-border/50 dark:border-sidebar-border"
                    >
                        <td
                            colspan="3"
                            class="px-4 py-6 text-center text-muted-foreground"
                        >
                            No roles found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
