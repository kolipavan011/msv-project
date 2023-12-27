<template>
    <div class="main">
        <PageHeader :title="$route.meta.title" />

        <main class="container-fluid">
            <h2>Home</h2>
        </main>
    </div>
</template>
<script>
import PageHeader from "../components/Header";

export default {
    name: "home",
    components: {
        PageHeader,
    },
};
</script>
