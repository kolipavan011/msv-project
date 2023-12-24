<template>
    <div class="container-fluid">
        <div class="row vh-100">
            <div id="app-sidebar" class="col-md-1 bg-dark d-none d-md-block">
                <div
                    class="icon-list d-flex flex-column align-items-center gap-4 my-2"
                >
                    <router-link
                        to="/dash"
                        class="icon-item text-white text-center"
                    >
                        <AccountIcon :size="42" />
                        <div>Dashmin</div>
                    </router-link>
                    <router-link
                        to="/posts"
                        class="icon-item text-white text-center"
                    >
                        <NoteIcon :size="42" />
                        <div>Posts</div>
                    </router-link>
                    <div class="icon-item text-white text-center">
                        <NotebookIcon :size="42" />
                        <div>Category</div>
                    </div>
                    <div class="icon-item text-white text-center">
                        <TagIcon :size="42" />
                        <div>Tags</div>
                    </div>
                    <div class="icon-item text-white text-center">
                        <BookIcon :size="42" />
                        <div>Pages</div>
                    </div>
                    <div class="icon-item text-white text-center">
                        <RobotIcon :size="42" />
                        <div>Vidbot</div>
                    </div>
                    <div class="icon-item text-white text-center">
                        <SettingIcon :size="42" />
                        <div>Setting</div>
                    </div>
                </div>
            </div>
            <div id="app-main" class="col-md-11">
                <header class="header mb-3">
                    <page-header />
                </header>
                <main>
                    <router-view></router-view>
                </main>
            </div>
        </div>
    </div>
</template>
<script>
import GridIcon from "icon/Grid";
import TagIcon from "icon/Tag";
import AccountIcon from "icon/AccountSupervisor";
import BookIcon from "icon/Book";
import RobotIcon from "icon/Robot";
import NoteIcon from "icon/Note";
import NotebookIcon from "icon/NoteBook";
import SettingIcon from "icon/AccountSettings";
import PageHeader from "../components/Header";

export default {
    name: "app",
    components: {
        TagIcon,
        SettingIcon,
        GridIcon,
        AccountIcon,
        BookIcon,
        RobotIcon,
        NotebookIcon,
        NoteIcon,
        PageHeader,
    },
};
</script>
