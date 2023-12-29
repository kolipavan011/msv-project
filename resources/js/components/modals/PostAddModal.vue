<template>
    <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">
            Add new {{ $route.name }}
        </h5>
        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
            @click="close"
        ></button>
    </div>
    <div class="modal-body">
        <label for="input1" class="form-label d-none">Date</label>
        <input
            type="text"
            class="form-control"
            id="published_at"
            style="outline: 0"
            v-model="title"
            placeholder="Add Title ..."
        />
    </div>
    <div class="modal-footer">
        <button
            type="button"
            class="btn btn-secondary"
            data-bs-dismiss="modal"
            @click="close"
        >
            Cancel
        </button>
        <button type="button" class="btn btn-primary" @click="addPost">
            Create
        </button>
    </div>
</template>
<script>
export default {
    name: "PostAddModal",

    data() {
        return {
            title: null,
        };
    },

    methods: {
        addPost() {
            this.request()
                .post("/posts/create", {
                    type: this.$route.meta.type,
                    title: this.title,
                })
                .then(({ data }) => {
                    this.$router.push({
                        name: "posts.edit",
                        params: { id: data.id },
                    });
                    this.$vbsModal.close();
                })
                .catch((error) => console.log(error));
        },
        close() {
            this.$vbsModal.close();
        },
    },
};
</script>
