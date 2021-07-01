<template>
    <div class="relative">
        <div class="vx-navbar-wrapper nav-menu-wrapper">
            <vs-navbar
                class="vx-navbar navbar-custom navbar-skelton"
                :color="navbarColor"
            >
                <ul class="menu-items flex flex-wrap w-full items-center">
                    <li
                        class="menu-item"
                        v-for="(item, index) in navMenuItems"
                        :key="index"
                        :class="{
                            'mr-2': !(navMenuItems.length === index + 1)
                        }"
                    >
                        <!-- If header -->
                        <template v-if="item.header">
                            <h-nav-menu-header
                                :header="item"
                                class="menu-header relative"
                            />
                        </template>

                        <!-- If it's group -->
                        <template v-else-if="item.submenu">
                            <h-nav-menu-group
                                class="menu-group relative py-4"
                                bottom
                                :key="`group-${index}`"
                                :group="item"
                                :groupIndex="index"
                                :open="checkGrpChildrenActive(item)"
                            />
                        </template>

                        <!-- If it's link -->
                        <div v-else-if="item.url" class="menu-link">
                            <h-nav-menu-item
                                class="relative py-4 cursor-pointer"
                                :to="item.slug !== 'external' ? item.url : null"
                                :href="
                                    item.slug === 'external' ? item.url : null
                                "
                                :icon="item.icon"
                                :target="item.target"
                                :isDisabled="item.isDisabled"
                                :slug="item.slug"
                            >
                                <span class="truncate">{{ item.name }}</span>
                                <vs-chip :color="item.tagColor" v-if="item.tag"
                                    >{{ item.tag }}
                                </vs-chip>
                            </h-nav-menu-item>
                        </div>
                    </li>
                    <!-- <li class="menu-item">
                        TO nya belum nemu

                        <div class="menu-link ml-3" v-on:click="goCDMS()">
                            <h-nav-menu-item
                                class="menu-group relative py-4"

                            >
                                Dashboard
                            </h-nav-menu-item>
                        </div>
                    </li> -->
                </ul>
                <div class="wkwk">
                    <div class="flex">
                        <ProfileDropDown />
                        <LoginDropDown />
                    </div>
                </div>
            </vs-navbar>
        </div>
    </div>
</template>

<script>
import ProfileDropDown from '../navbar/components/ProfileDropDown';
import LoginDropDown      from '../navbar/components/LoginDropDown.vue'
import HNavMenuGroup from "./HorizontalNavMenuGroup.vue";
import HNavMenuHeader from "./HorizontalNavMenuHeader.vue";
import HNavMenuItem from "./HorizontalNavMenuItem.vue";

export default {
    data() {
        return {
            link: "/cdms"
        };
    },
    props: {
        // navbarColor  : { type: String, default: "#fff", },
        navMenuItems: { type: Array, required: true }
    },
    components: {
        HNavMenuGroup,
        HNavMenuHeader,
        HNavMenuItem,
        ProfileDropDown,
        LoginDropDown
    },
    computed: {
        navbarColor() {
            return this.$store.state.theme === "dark" ? "#10163a" : "#fff";
        }
    },

    methods: {
        cek() {
            console.log(this.$route.fullPath);
        },
        goCDMS() {
            // console.log('click cok')
            // console.log(this.$router, 'wkwk')

            this.$router.push('/cdms').catch(() => {})
        },
        checkGrpChildrenActive(group) {
            // console.log(gruop, "group, 1k1k1k");
            const path = this.$route.fullPath;
            let active = false;
            const routeParent = this.$route.meta
                ? this.$route.meta.parent
                : undefined;

            if (group.submenu) {
                group.submenu.forEach(item => {
                    if (active) return true;
                    if (
                        (path === item.url || routeParent === item.slug) &&
                        item.url
                    )
                        active = true;
                    else if (item.submenu) this.checkGrpChildrenActive(item);
                });
            }

            return active;
        }
    },
    mounted() {
        // this.cek()
    },

};
</script>

<style lang="scss">
@import "@sass/vuexy/components/horizontalNavMenu.scss";
</style>
