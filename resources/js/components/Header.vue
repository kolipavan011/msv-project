<template>
    <header class="header mb-3">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand text-uppercase" href="#">{{ title }}</a>
            </div>
        </nav>
    </header>
</template>
<script>
export default {
    name: "page-header",

    props: {
        title: {
            type: String,
            required: true,
        },
    },
};
</script>
