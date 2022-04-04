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
                        <small>
                        <div class="d-flex flex-row align-items-center align-content-center post-title">
                            <span class="mr-2 comments">
                                <div class="spinner-border spinner-border-sm text-primary" role="status" v-if="isRetrievingCommentCount">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <span v-else>{{commentsCount}}</span>&nbsp;comments&nbsp;
                            </span>

                            <span class="ml-1 mr-2"><strong>Admin</strong></span>
                            <span class="mr-2 mb-1 dot bg-success"></span>
                            <span>6 hours ago</span>
                        </div>
                        </small>
                        <div class="d-flex flex-row">
                            <span class="btn btn-sm btn-link ml-0 pl-0">
                                <small @click="changeUser">Change user</small>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="comment-bottom bg-white p-2 px-4">
                    <reply ref="mainCommentBox" :saveComment="saveComment"></reply>
                    <template v-for="(comment_details, index) in parentComments">
                        <comment :key="'comment_' + comment_details.id"
                                 :level="1"
                                 :details="comment_details"
                                 :margin="0"
                                 :deleted="deleted"
                                 :index="index"
                        ></comment>
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
import reply from './reply';
export default {
    data(){
     return {
         parentComments: [],
         comment: '',
         isRetrievingCommentCount: true,
         isRetrievingComments: true,
         isSavingComment: false
     }
    },
    components:{ comment, reply },
    computed:{
        // We map the products state from our vuex store for neater code when accessing.
        ...mapState(['user', 'commentsCount'])
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
                  for(let i = 0; i < responseData['records'].length; i++){
                      responseData['records'][i]['comment_timestamp'] = new Date(responseData['records'][i]['created_at']);
                  }
                  this.parentComments = [...this.parentComments, ...responseData['records']];
                  let self = this;
                  setTimeout(function(){
                      self.updateTime();
                  }, 1000);
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
                    this.$store.dispatch('setCommentsCount', responseData['count']);
                }
                this.isRetrievingCommentCount = false
            }).catch((error) =>{
                alert("A js error has occurred. Please contact system admin through email: familyinspiredprojects@gmail.com");
            });
        },
        saveComment: function(){
            this.$refs.mainCommentBox.$data.isSavingComment = true;
            axios.post('/create_comment', {
                user_id: this.user.id,
                comment: this.$refs.mainCommentBox.$data.comment,
                parentID: 0
            }).then((response) => {
                let responseData = response.data;

                if (responseData.status === 0) {
                    if(responseData.message !== ""){
                        alert(responseData.message);
                    }else{
                        this.errors = responseData['errors'];
                    }
                } else {
                    this.$store.dispatch('setCommentsCount', this.commentsCount + 1);
                    responseData['record'].user = this.user;
                    responseData['record']['comment_timestamp'] = new Date(responseData['record']['created_at']);
                    this.parentComments.unshift(responseData['record']);
                    this.$refs.mainCommentBox.$data.comment = "";
                }
                this.$refs.mainCommentBox.$data.isSavingComment = false;
            }).catch((error) => {
                this.$refs.mainCommentBox.$data.isSavingComment = false;
                alert("A js error has occurred. Please contact system admin through email: familyinspiredprojects@gmail.com")
            });
        },
        changeUser: function(){
            let self = this;
            this.$store.dispatch('setSelectedRoute', '/user').then(function(val){
                self.$router.push('/user');
            });
        },
        deleted: function(index, commentCount){
            this.parentComments.splice(index, 1);
            this.$store.dispatch('setCommentsCount', commentCount);
        },
        updateTime: function(){
            let self = this;
            let commentTime = new Promise((resolve, reject) => {
                setTimeout(() => {
                    resolve();
                }, 1000);
            });
            commentTime.then(function(){
                for(let i = 0; i < self.parentComments.length; i++){
                    let currentTimestamp = new Date(self.parentComments[i]['comment_timestamp']);
                    currentTimestamp.setSeconds(currentTimestamp.getSeconds() - 1);
                    self.parentComments[i]['comment_timestamp'] = currentTimestamp;
                }
                self.updateTime();
            });
        }
    },
    mounted(){
        this.retrieveTotalComments();
        this.retrieveComments();
    }
}
</script>
