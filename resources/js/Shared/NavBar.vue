<template>
    <div class="flex overflow-hidden bg-white">
        <aside id="sidebar"
            class="fixed z-20 h-full top-0 left-0 pt-14 flex lg:flex flex-shrink-0 flex-col w-64 transition-width duration-75"
            aria-label="Sidebar">
            <div class="relative flex-1 flex flex-col min-h-0 border-r border-gray-300 bg-white pt-0">
                <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
                    <div class="flex-1 px-3 bg-white divide-y space-y-1">
                        <ul class="flex flex-col h-full">
                            <div class="space-y-2 pb-2 flex-1">
                                <!-- Dashboard -->
                                <NavLink href="/" :active="$page.component === 'Dashboard'" title="Dashboard">
                                    <i class="fa-solid fa-chart-line"></i>
                                </NavLink>
                                <!-- Orders -->
                                <NavLink href="/orders" :active="$page.component === 'Orders/Index'" title="Pedidos">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                </NavLink>
                                <!-- Clients -->
                                <NavLink href="/clients" :active="$page.component === 'Clients/Index'" title="Clientes">
                                    <i class="fa-solid fa-address-book"></i>
                                </NavLink>
                                <!-- Products -->
                                <NavLink href="/products" :active="$page.component === 'Products/Index'"
                                    title="Produtos">
                                    <i class="fa-solid fa-bag-shopping"></i>
                                </NavLink>
                                <!-- Users -->
                                <NavLink href="/users" :active="$page.component === 'Users/Index'" title="Usuários">
                                    <i class="fa-solid fa-users"></i>
                                </NavLink>
                            </div>

                            <div class="space-y-2">
                                <!-- Meu Perfil -->
                                <NavLink :href="`/users/${id}`" :active="$page.url === `/users/${id}`"
                                    title="Meu Perfil">
                                    <i class="fa-solid fa-user"></i>
                                </NavLink>

                                <!-- Sair -->
                                <NavLink href="/logout" method="POST" title="Sair">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                </NavLink>
                            </div>
                        </ul>
                    </div>

                </div>
            </div>
        </aside>
    </div>
</template>

<script setup>
import NavLink from './NavLink.vue';
import { usePage } from '@inertiajs/vue3';

const { props } = usePage();

const id = props.auth?.user?.id ?? null;

</script>
