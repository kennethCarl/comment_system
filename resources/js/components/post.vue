<style>
body {
    background-color: #eee
}

.bdge {
    height: 21px;
    background-color: orange;
    color: #fff;
    font-size: 11px;
    padding: 8px;
    border-radius: 4px;
    line-height: 3px
}

.comments {
    text-decoration: underline;
    text-underline-position: under;
    cursor: pointer
}

.dot {
    height: 7px;
    width: 7px;
    margin-top: 3px;
    background-color: #bbb;
    border-radius: 50%;
    display: inline-block
}

.hit-voting:hover {
    color: blue
}

.hit-voting {
    cursor: pointer
}
</style>
<template>
    <div class="mt-5 mb-5 ml-5 mr-5">
        <div class="d-flex justify-content-center row">
            <div class="d-flex flex-column col-md-12">
                <div class="d-flex flex-row align-items-center text-left comment-top p-2 bg-white border-bottom px-4">
                    <div class="profile-image">
                        <img class="rounded-circle" src="/storage/images/female1.jpeg" width="70">
                    </div>
                    <div class="d-flex flex-column ml-3">
                        <div class="d-flex flex-row post-title">
                            <h5>Why Aloware is a good company to work? </h5>
                        </div>
                        <div class="d-flex flex-row align-items-center align-content-center post-title">
                            <span class="mr-2 comments">
                                <div class="spinner-border spinner-border-sm text-primary" role="status" v-if="isRetrievingCommentCount">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <span v-else>{{commentsCount}}</span>&nbsp;comments&nbsp;
                            </span>

                            <span class="ml-1 mr-2">Admin</span>
                            <span class="mr-2 mb-1 dot bg-success"></span>
                            <span>6 hours ago</span>
                        </div>
                        <div class="d-flex flex-row">
                            <span class="btn btn-sm btn-link ml-0 pl-0">
                                <small @click="changeUser">Change user</small>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="comment-bottom bg-white p-2 px-4">
                    <div class="d-flex flex-row add-comment-section mt-4 mb-4">
                        <img class="img-fluid img-responsive rounded-circle mr-2" :src="'/storage/images/' + user['avatar']" width="38">
                        <input v-model="comment" v-on:keyup.enter="sendMessage" type="text" class="form-control form-control-sm mr-3 mt-1"
                               :placeholder="'Commenting as ' + user['alias']"/>
                        <button class="btn btn-sm btn-primary" type="button" @click="sendMessage" :disabled="isSavingComment">
                            <span v-if="isSavingComment" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            <span v-else>Send</span>
                        </button>
                    </div>

                    <template v-for="comment_details in parentComments">
                        <comment :level="1" :details="comment_details" :margin="0"></comment>
                    </template>
                    <span class="btn btn-sm btn-link" @click="retrieveComments">
                        <small class="mr-2">See more comments...</small>
                        <div class="spinner-border spinner-border-sm text-primary" role="status" v-if="isRetrievingComments">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapState} from "vuex";
import comment from './comment';
export default {
    data(){
     return {
         commentsCount: 0,
         parentComments: [],
         comment: '',
         isRetrievingCommentCount: true,
         isRetrievingComments: true,
         isSavingComment: false
     }
    },
    components:{ comment },
    computed:{
        // We map the products state from our vuex store for neater code when accessing.
        ...mapState(['user'])
    },
    methods:{
        retrieveComments: function(){
            this.isRetrievingComments = true;
          axios.post('/retrieve_comments', {
              currentLength: this.parentComments.length,
          }).then((response) =>{
              let responseData = response.data;
              if(responseData.status === 0){
                  alert(responseData.message);
              }else{
                  this.parentComments = [...this.parentComments, ...responseData['records']];
              }
              this.isRetrievingComments = false;
          }).catch((error) =>{
              this.isRetrievingComments = false;
              alert("A js error has occurred. Please contact system admin through email: familyinspiredprojects@gmail.com");
          });
        },
        retrieveTotalComments: function(){
            axios.get('/retrieve_total_comments').then((response) =>{
                let responseData = response.data;
                if(responseData.status === 0){
                    alert(responseData.message);
                }else{
                    this.commentsCount = responseData['count']
                }
                this.isRetrievingCommentCount = false
            }).catch((error) =>{
                alert("A js error has occurred. Please contact system admin through email: familyinspiredprojects@gmail.com");
            });
        },
        sendMessage: function(){
            this.isSavingComment = true;
        },
        changeUser: function(){
            let self = this;
            this.$store.dispatch('setSelectedRoute', '/user').then(function(val){
                self.$router.push('/user');
            });
        }
    },
    mounted(){
        this.retrieveTotalComments();
        this.retrieveComments();
    }
}
</script>
