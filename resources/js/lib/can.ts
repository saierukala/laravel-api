import type { InertiaConfig } from '@inertiajs/core';
import { usePage } from '@inertiajs/vue3';

type SharedPageProps = InertiaConfig['sharedPageProps'];

export const can = (permission: string): boolean => {
    const page = usePage<SharedPageProps>();

    return page.props.auth.permissions.includes(permission);
};
