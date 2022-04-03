<style>
.gradient-custom-3 {
    /* fallback for old browsers */
    background: #84fab0;

    /* Chrome 10-25, Safari 5.1-6 */
    background: -webkit-linear-gradient(to right, rgba(132, 250, 176, 0.5), rgba(143, 211, 244, 0.5));

    /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
    background: linear-gradient(to right, rgba(132, 250, 176, 0.5), rgba(143, 211, 244, 0.5))
}
.gradient-custom-4 {
    /* fallback for old browsers */
    background: #84fab0;

    /* Chrome 10-25, Safari 5.1-6 */
    background: -webkit-linear-gradient(to right, rgba(132, 250, 176, 1), rgba(143, 211, 244, 1));

    /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
    background: linear-gradient(to right, rgba(132, 250, 176, 1), rgba(143, 211, 244, 1))
}
</style>
<template>
    <section class="vh-100 bg-image" style="background-image: url('https://mdbcdn.b-cdn.net/img/Photos/new-templates/search-box/img4.webp');">
        <div class="mask d-flex align-items-center h-100 gradient-custom-3">
            <div class="container">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-12 col-md-9 col-lg-7 col-xl-6 mt-3 mb-3">
                        <div class="card" style="border-radius: 15px;">
                            <div class="card-body p-5">
                                <h2 class="text-center mb-5">Comment System</h2>
                                <div class="form-outline mb-4">
                                    <div class="d-flex justify-content-center row">
                                        <img class=" rounded-circle" :src="'/storage/images/' + user['avatar']" width="70">
                                    </div>
                                    <label class="form-label">Select Avatar</label>
                                    <select class="form-control form-control-lg" v-model="user['avatar']">
                                        <option v-for="avatar in avatars" :value="avatar.value">{{avatar.name}}</option>
                                    </select>
                                </div>
                                <div class="form-outline mb-4">
                                    <label class="form-label" for="userName">Alias<span class="text-danger ml-1">{{errors['alias']}}</span></label>
                                    <input maxlength="10" type="text" v-model="user.alias" id="userName" class="form-control form-control-lg" />
                                </div>
                                <div class="d-flex justify-content-center">
                                    <button type="button" class="btn btn-success btn-block btn-lg gradient-custom-4 text-body" @click="onProceed" :disabled="onProcess">Proceed</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import {mapState} from "vuex";
export default {
    data(){
        return {
            errors: {
                alias: '',
                avatar: ''
            },
            onProcess: false,
            parentComments: [],
            avatars: [
                {name: 'Man Alt 1', value:'male1.png'},
                {name: 'Man Alt 2', value:'male2.jpeg'},
                {name: 'Woman Alt 1', value:'female1.jpeg'},
                {name: 'Woman Alt 2', value:'female2.png'}
            ]
        }
    },
    computed:{
        // We map the products state from our vuex store for neater code when accessing.
        ...mapState(['user'])
    },
    watch:{
        user: {
            handler(val){
                console.log(val);
                this.$store.dispatch('setUser', val);
            },
            deep: true
        }
    },
    methods:{
        onProceed: function(){
            this.onProcess = true;
            let self = this;

            if(this.user.alias.toString().trim() === ""){
                this.errors.alias = 'is required!';
                this.onProcess = false;
            }else {
                axios.post('/create_user', this.user).then((response) => {
                    this.onProcess = false;
                    let responseData = response.data;

                    if (responseData.status === 0) {
                        if(responseData.message !== ""){
                            alert(responseData.message);
                        }else{
                            this.errors = responseData['errors'];
                        }
                    } else {
                        this.record = responseData['record'];
                        this.$store.dispatch('setUser', this.record);
                        this.$store.dispatch('setSelectedRoute', '/post').then(function(val){
                            self.$router.push({ path: '/post'});
                        })
                    }
                }).catch((error) => {
                    this.onProcess = false;
                    alert("A js error has occurred. Please contact system admin through email: familyinspiredprojects@gmail.com")
                });
            }
        }
    }
}
</script>
