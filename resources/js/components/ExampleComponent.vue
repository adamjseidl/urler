<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h1 class="">Urler</h1>
                <hr>
                <div class="form-group"></div>
                        <div class="col-md-10">
                            <label>Add New URL</label>
                            <input
                               v-model="newURL"
                               name="newURL"
                               autocomplete="off"
                               placeholder="http://www.example.com"
                               class="form-control"
                            >
                            <button type="submit"
                                    class="btn btn-primary"
                                    style="background-color:#007bff"
                                    @click="addURL"
                            >Add URL</button>
                        </div>
                </div>
            </div>
    </div>
</template>

<script>
    import { ref } from 'vue';
    import axios from 'axios';
    export default {
        setup() {
            const data = ref({
                newUrl: "",
            });
        },
        methods: {
            async addURL() {
                console.log('Add URL');
                try {
                    console.log();
                    await axios.post('http://localhost/api/v1/url', data.value.newURL);
                    //TODO add to list, or reload list
                } catch(err) {
                    //TODO handle error
                    console.log(err);
                }
            },
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
