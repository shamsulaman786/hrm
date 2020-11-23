<!DOCTYPE html>
<html>
<head>
  <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Material+Icons" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
  <style type="text/css">
    img {
      border-radius: 50%;
    }
  </style>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
</head>
<body>
<div id="app">
  <v-app id="inspire">
    <v-app id="inspire">
      <v-navigation-drawer
        v-model="drawer"
        app
      >
        <v-list dense>
          <v-list-item link>
            <v-list-item-action>
              <v-icon>mdi-home</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>Home</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item link>
            <v-list-item-action>
              <v-icon>mdi-account</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>Employee</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list>
      </v-navigation-drawer>
  
      <v-app-bar
        app
        color="indigo"
        dark
      >
        <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
        <v-toolbar-title>Application</v-toolbar-title>
      </v-app-bar>
  
      <v-main>
        <v-container
          class="fill-height"
          fluid
        >
          <v-layout
            align="top"
            justify="center"
          >
            <v-col >
<template>
    <v-data-table
      :headers="headers"
      :items="desserts"
      sort-by="name"
      class="elevation-1"
    >
      <template v-slot:item.avatar="{ item }">
        <div class="avatar img-fluid img-circle">
          <v-img :src="item.avatar" style="width: 50px; height: 50px">
          
        </div>
      </template>
      <template v-slot:item.experience="{ item }">
        <p >@{{getExperience(item.joining,item.leaving)}}</p>
      </template>

      <template v-slot:top>
        <v-toolbar flat color="white">
          <v-toolbar-title>User Records</v-toolbar-title>
          <v-divider
            class="mx-4"
            inset
            vertical
          ></v-divider>
          <v-spacer></v-spacer>
          <v-dialog v-model="dialog" max-width="500px">
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                color="primary"
                dark
                class="mb-2"
                v-bind="attrs"
                v-on="on"
              >Add new</v-btn>
            </template>
            <v-card>
              <v-card-title>
                <span class="headline">@{{ formTitle }}</span>
              </v-card-title>
  
              <v-card-text>
                <v-container>

                  <v-form
                    ref="form"
                    v-model="valid"
                    lazy-validation
                  >
                    <v-row>

                      <v-col cols="auto" sm="6" md="12">
                        <v-text-field v-model="editedItem.name" 
                          :counter="20"
                          :rules="nameRules"
                          label="Name"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col cols="auto" sm="6" md="12">
                        <v-text-field v-model="editedItem.email"             
                          :rules="emailRules"
                          label="E-mail"
                          required
                         ></v-text-field>
                      </v-col>
                      </v-col>
                      <v-col cols="auto" sm="6" md="12">
                        <v-dialog
                          ref="joiningDialog"
                          v-model="joiningModal"
                          :return-value.sync="editedItem.joining"
                          persistent
                          width="290px"
                        >
                          <template v-slot:activator="{ on, attrs }">
                            <v-text-field
                              v-model="editedItem.joining"
                              label="Date of joining"
                              prepend-icon="event"
                              readonly
                              required
                              v-bind="attrs"
                              v-on="on"
                            ></v-text-field>
                          </template>
                          <v-date-picker v-model="editedItem.joining" scrollable>
                            <v-spacer></v-spacer>
                            <v-btn text color="primary" @click="joiningModal = false">Cancel</v-btn>
                            <v-btn text color="primary" @click="$refs.joiningDialog.save(editedItem.joining)">OK</v-btn>
                          </v-date-picker>
                        </v-dialog>
                      </v-col>
                      <v-col cols="auto" sm="8" md="8">
                        <v-dialog
                          ref="leavingDialog"
                          v-model="leavingModal"
                          :return-value.sync=editedItem.leaving
                          persistent
                          width="290px"
                        >
                          <template v-slot:activator="{ on, attrs }">
                            <v-text-field
                              v-model=editedItem.leaving
                              label="Date of leaving"

                              prepend-icon="event"
                              readonly
                              v-bind="attrs"
                              v-on="on"
                            ></v-text-field>
                          </template>
                          <template v-slot:activator="{ on, attrs }">
                            <v-text-field
                              v-model=editedItem.leaving
                              label="Date of leaving"
                              prepend-icon="event"
                              readonly
                              v-bind="attrs"
                              v-on="on"
                              :disabled=isPresent=editedItem.leaving===null
                            ></v-text-field>
                          </template>

                          <v-date-picker v-model=editedItem.leaving scrollable>
                            <v-spacer></v-spacer>
                            <v-btn text color="primary" @click="leavingModal = false">Cancel</v-btn>
                            <v-btn text color="primary" @click="$refs.leavingDialog.save(editedItem.leaving)">OK</v-btn>
                          </v-date-picker>
                        </v-dialog>
                      </v-col>
                      <v-col cols="auto" sm="4" md="4">
                          <template>
                              <v-checkbox
                                v-model="isPresent"
                                hide-details
                                class="shrink mr-2 mt-0"
                                label="present"
                              ></v-checkbox>
                          </template>

                      </v-col>
                      <v-col cols="auto" sm="12" md="12">
                        <v-file-input
                          @change='upload_avatar'
                          :rules="rules"
                          accept="image/png, image/jpeg, image/bmp"
                          placeholder="Pick an avatar"
                          prepend-icon="mdi-camera"
                          label="profile picture"
                        ></v-file-input>
                  
                        <!-- <input type="file" @change='upload_avatar' name="avatar"> -->

                      </v-col>
                    </v-row>
                  </v-form>
                </v-container>
              </v-card-text>
  
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                <v-btn color="blue darken-1" text :disabled="!valid" @click="save">Save</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-toolbar>
      </template>
     
      <template v-slot:item.actions="{ item }">
        <v-icon
          small
          class="mr-2"
          @click="editItem(item)"
        >
          mdi-pencil
        </v-icon>
        <v-icon
          small
          @click="deleteItem(item)"
        >
          mdi-delete
        </v-icon>
      </template>
      <template v-slot:no-data>
        <v-btn color="primary" @click="initialize">Reset</v-btn>
      </template>
    </v-data-table>
  
</template>    
    </v-data-table>
      <v-snackbar v-model="showSnackbar" :timeout="snackTimeout" :color="snackbarColor" :top='topRightSnackbar' :right='topRightSnackbar'>
        @{{ snackbarMessage }}
      </v-snackbar>

            </v-col>
          </v-row>
        </v-container>
      </v-main>
      <v-footer
        color="indigo"
        app
      >
        <span class="white--text">&copy; @{{ new Date().getFullYear() }}</span>
      </v-footer>
    </v-app>
  </v-app>
</div>

  <script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.js"></script>
  <script>

new Vue({
  el: '#app',
  vuetify: new Vuetify(),
  props: {
    source: String,
  },
  data: () => ({
    valid: true,
      nameRules: [
        v => !!v || 'Name is required',
        v => (v && v.length <= 10) || 'Name must be less than 10 characters',
      ],
      emailRules: [
        v => !!v || 'E-mail is required',
        v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
      ],
    itemLeavingDate: '',
      topRightSnackbar:false,
      snackTimeout: 3000,
      showSnackbar: false,
      snackbarColor: '',
      snackbarMessage: '',
    employee: [],
    photo: null,
    avatar: null,
    drawer: null,
    rules: [
      value => !value || value.size < 2000000 || 'Avatar size should be less than 2 MB!',
    ],
    dialog: false,
    joiningModal: false,
    leavingModal: false,
    leavingDate: new Date().toISOString().substr(0, 10),
    joiningDate: new Date().toISOString().substr(0, 10),
    formTitle: 'Add new employee',
    modal: false,
    modal2: false,
    headers: [
    // {text: 'id',value: 'id',type:'hidden'},
      {
        text: 'Avatar',
        align: 'start',
        sortable: false,
        value: 'avatar',
      },
      { text: 'Name', value: 'name' },
      { text: 'Email', value: 'email',type:'email' },
      { text: 'Experience', value: 'experience'},
      { text: 'Actions', value: 'actions', sortable: false },
    ],
    isPresent: false,
    desserts: [],
    editedIndex: -1,
    editedItem: {
      avatar: '',
      joining: new Date().toISOString().substr(0, 10),
      leaving: new Date().toISOString().substr(0, 10),
      name: '',
      email: '',
    },
    defaultItem: {
      avatar: '',
      name: '',
      joining: new Date().toISOString().substr(0, 10),
      leaving: new Date().toISOString().substr(0, 10),
      email: '',
    },
  }),

  computed: {
    formTitle () {
      return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
    },
  },
  mounted(){
    this.getEmployee();
  },

  watch: {
    dialog (val) {
      val || this.close()
    },
  },

  created () {
    this.initialize()
  },

  methods: {
        getEmployee(){
          axios.get('get-employee')
          .then(res => {
            var count =1
            // for (const iterator of res.data) {
            //   iterator.srno=count
            //   count++
            // }
            this.desserts = res.data;
          })
          .catch(err => {
            console.error(err);
          })
        },   
    setEmployee(item){
      // console.log(this.photo);
      let leaving_date = null;
        const data = new FormData();
        data.append('photo', this.photo);
        if (!this.isPresent) {
           leaving_date = item.leaving;
        }
        const json = JSON.stringify({
            name: item.name,
            email: item.email,
            joining_date: item.joining,
            leaving_date: leaving_date,
        });
        data.append('employee', json);
        axios.post("set-employee", data)
      .then(res => {

        // console.log(res)
        this.showSnackbar=true,
        this.snackbarColor='success',
        this.snackbarMessage='User created successfully!'
      })
      .catch(err => {
        console.error(err);
            this.snackbarMessage = "Oops! Something went wrong!";
            this.snackbarColor = 'error';
            this.showSnackbar = true;
      });
      setTimeout(() => this.getEmployee(), 2000);

    },

    upload_avatar(file){
      // console.log(e);
      // alert();
      // let file = file;
      // 
             this.photo = file;

        let reader = new FileReader();  

        if(file['size'] < 2111775)
        {
            reader.onloadend = (file) => {
            //console.log('RESULT', reader.result)
             this.avatar = reader.result;
            }              
             reader.readAsDataURL(file);
        }else{
            alert('File size can not be bigger than 2 MB')
        }
    },
    initialize () {
      this.desserts = [
        {
          // avatar:'http://127.0.0.1:8000/hrm/resources/assets/images/avatar.jpeg',
          id: '1',
          avatar:'assets/images/avatar.jpeg',
          name: 'Shamsul Haque',
          email: 'shamsul@cnv.co.in',
          joining: '2019-04-01',
          leaving: '2020-11-22',
          // isPresent: false,
        },
        {
          id: '2',
          avatar:'https://cdn.vuetifyjs.com/images/lists/2.jpg',
          name: 'Shamsul Haque',
          email: 'shamsul@developer.com',
          joining: '2019-04-01',
          leaving: '2020-11-22',
          // isPresent: false,
        },
      ]
    },
// getPresency(){

// },
    editItem (item) {
      this.editedIndex = this.desserts.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialog = true
    },

    deleteItem (item) {
      const index = this.desserts.indexOf(item);
      let decision = confirm('Are you sure you want to delete this item?') && this.desserts.splice(index, 1);
      if (decision) {
        axios.delete('employee/'+item.id,'')
          .then(res => {
            // console.log(res.data);
            this.showSnackbar=true,
            this.snackbarColor='success',
            this.snackbarMessage='User deleted successfully!'

      setTimeout(() => this.getEmployee(), 2000);
          })
          .catch(err => {
            this.showSnackbar=true,
            this.snackbarColor='danger',
            this.snackbarMessage='Oops Something went wrong!'

            console.error(err);
          })
        }
      },   

    close () {
      this.dialog = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },

    save () {
      if (this.editedIndex > -1) {
        Object.assign(this.desserts[this.editedIndex], this.editedItem)
      } else {
        this.desserts.push(this.editedItem);
        this.setEmployee(this.editedItem);
      }
      this.close()
    },
    getExperience(d1,d2){
      let result =0;
      let a = new Date(d1);
      let b = null;
      if (!d2) {
        b= new Date();
      }else{
        b = new Date(d2);
      }
      let difdt = new Date(b - a);
      result = (difdt.toISOString().slice(0, 4) - 1970) + "Y " + (difdt.getMonth()+1) + "M " + (difdt.getDate()) + "D";
      return result;
    },

  },
  computed: {
    // isPresent(leavingDate){
    //   if (!leavingDate) {
    //     return true;
    //   }else{
    //     return false;
    //   }
    // }

  },
    watch: {
      isPresent: function (newValue, oldValue) {
        if (newValue==true) {
          this.editedItem.leaving=null;

        }else{
          this.editedItem.leaving=new Date().toISOString().substr(0, 10);
        } 

      }
    },

})  </script>
</body>
</html>