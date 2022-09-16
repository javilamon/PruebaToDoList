<!DOCTYPE html>
<html>

<head>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.4/axios.min.js"
        integrity="sha512-lTLt+W7MrmDfKam+r3D2LURu0F47a3QaW5nF0c6Hl0JDZ57ruei+ovbg7BrZ+0bjVJ5YgzsAWE+RreERbpPE1g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body>
    @section('app')

    <div id="app">
        <v-app>
            <v-main>
                <v-container fill-height>
                    <v-row justify="center" aling="center">
                        <v-col cols='7'>
                            <v-card>
                                <v-container fluid>
                                    <v-row>
                                        <v-col cols="12" class="d-flex justify-content-center">

                                            <v-text-field outlined dense label="to do" v-model="todoedition"
                                                class="mx-4" clearable>

                                            </v-text-field>
                                            <v-btn @click="save" icon :loading="loading">
                                                <v-icon>

                                                    mdi-plus

                                                </v-icon>
                                            </v-btn>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-data-table :headers="headers" :items="desserts" sort-by="calories"
                                                class="elevation-1">
                                                <template v-slot:top>
                                                    <v-toolbar flat>
                                                        <v-toolbar-title>To Do List!</v-toolbar-title>
                                                        <v-divider class="mx-4" inset vertical></v-divider>
                                                        <v-spacer></v-spacer>
                                                        <v-dialog v-model="dialogDelete" max-width="500px">
                                                            <v-card>
                                                                <v-card-title class="text-h5">Are you sure you want to
                                                                    delete this item?
                                                                </v-card-title>
                                                                <v-card-actions>
                                                                    <v-spacer></v-spacer>
                                                                    <v-btn color="blue darken-1" text
                                                                        @click="closeDelete">Cancel
                                                                    </v-btn>
                                                                    <v-btn color="blue darken-1" text
                                                                        @click="deleteItemConfirm">OK
                                                                    </v-btn>
                                                                    <v-spacer></v-spacer>
                                                                </v-card-actions>
                                                            </v-card>
                                                        </v-dialog>
                                                    </v-toolbar>
                                                </template>
                                                <template v-slot:item.content="{ item }">
                                                    <v-edit-dialog :return-value.sync="item.content" large
                                                        @save="editItem(item)">
                                                        <span class="text-decoration-line-through" v-if="item.done">
                                                            @{{item.content}}
                                                        </span>
                                                        <span v-else>
                                                            @{{item.content}}
                                                        </span>
                                                        <template v-slot:input>
                                                            <v-text-field v-model="item.content" label="Edit"
                                                                single-line counter :disabled="item.done">
                                                            </v-text-field>
                                                        </template>
                                                    </v-edit-dialog>
                                                </template>
                                                <template v-slot:item.done="{ item }">
                                                    <v-simple-checkbox v-model="item.done == 0 ? false : true"
                                                        @click="changeStatusToDoITem(item)"></v-simple-checkbox>
                                                </template>
                                                <template v-slot:item.actions="{ item }">
                                                    <v-icon small @click="deleteItem(item)">
                                                        mdi-delete
                                                    </v-icon>
                                                </template>
                                                <template v-slot:no-data>
                                                    <v-btn color="primary" @click="initialize">
                                                        Reset
                                                    </v-btn>
                                                </template>
                                            </v-data-table>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card>

                        </v-col>
                    </v-row>
                </v-container>
            </v-main>
        </v-app>
    </div>

    @show
</body>

<footer>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
    <script>
        new Vue({
            el: '#app',
            vuetify: new Vuetify(),
            data: () => ({
                dialog: false,
                dialogDelete: false,
                headers: [{
                        value: 'done',
                        sortable: false
                    }, {
                        text: 'To do',
                        value: 'content'
                    },
                    {
                        text: 'Actions',
                        value: 'actions',
                        sortable: false
                    },
                ],
                todoedition: null,
                desserts: [],
                editedIndex: -1,
                editedItem: {
                    name: '',
                    calories: 0,
                    fat: 0,
                    carbs: 0,
                    protein: 0,
                },
                defaultItem: {
                    name: '',
                    calories: 0,
                    fat: 0,
                    carbs: 0,
                    protein: 0,
                },
                loading: false
            }),

            computed: {
                formTitle() {
                    return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
                },
            },

            watch: {
                dialog(val) {
                    val || this.close()
                },
                dialogDelete(val) {
                    val || this.closeDelete()
                },
                todoedition(val) {
                    if (val != null) {
                        if (val.length != 0 && val != null) {
                            this.loading = true
                            var body = new Object()
                            body.textTodo = val

                            axios.post("http://127.0.0.1:8000/api/todo/getRecodorsTodoSearch", body).then((
                                res) => {
                                    if (res.data.length != 0) {
                                        this.desserts = res.data
                                    } else {
                                        this.initialize()
                                    }
                                }).finally(() => {
                                this.loading = false
                            })
                        } else {
                            this.initialize()
                        }
                    } else {
                        this.initialize()
                    }
                }
            },

            created() {
                this.initialize()

            },

            methods: {
                initialize() {

                    axios.get("http://127.0.0.1:8000/api/todo/getall").then((res) => {

                        this.desserts = res.data;

                    }).catch((e) => {}).finally(() => {})


                },

                editItem(item) {
                    this.editedIndex = this.desserts.indexOf(item)
                    this.editedItem = Object.assign({}, item)
                    this.dialog = true
                },

                deleteItem(item) {
                    this.editedIndex = this.desserts.indexOf(item)
                    this.editedItem = Object.assign({}, item)
                    this.dialogDelete = true
                },

                deleteItemConfirm() {
                    var body = new Object()
                    body.pk = this.desserts[this.editedIndex]["id"]
                    axios.post("http://127.0.0.1:8000/api/todo/delete", body).then((res) => {
                        if (res.data) {
                            this.desserts.splice(this.editedIndex, 1)

                        }
                    }).catch((e) => {}).finally(() => {
                        this.initialize()
                        this.closeDelete()
                    })



                },

                close() {
                    this.dialog = false
                    this.$nextTick(() => {
                        this.editedItem = Object.assign({}, this.defaultItem)
                        this.editedIndex = -1
                    })
                },

                closeDelete() {
                    this.dialogDelete = false
                    this.$nextTick(() => {
                        this.editedItem = Object.assign({}, this.defaultItem)
                        this.editedIndex = -1
                    })
                },


                save() {
                    var body = new Object()
                    body.content = this.todoedition

                    axios.post("http://127.0.0.1:8000/api/todo/new", body).then((res) => {
                        this.desserts.push(res.data)
                    }).catch((e) => {}).finally(() => {
                        this.todoedition = null
                    })
                },

                changeStatusToDoITem(item) {

                    var body = new Object()
                    body.pk = item.id
                    body.done = !item.done

                    axios.post("http://127.0.0.1:8000/api/todo/updateTodoDone", body).then((res) => {
                        if (res.data) {
                            const index = this.desserts.findIndex(obj => {
                                return obj.id === item.id;
                            })

                            item.done = !item.done
                            this.desserts.splice(index, 1, item)
                        }
                        //this.desserts.push(res.data)
                    }).catch((e) => {}).finally(() => {
                        this.todoedition = null
                    })

                },
                editItem(item) {
                    var body = new Object()
                    body.pk = item.id
                    body.textTodo = item.content

                    axios.post('http://127.0.0.1:8000/api/todo/updateItemTodoByPk', body).then((res) => {})
                        .catch((e) => {}).finally(() => {})
                }

            },
        })

    </script>
</footer>

</html>
