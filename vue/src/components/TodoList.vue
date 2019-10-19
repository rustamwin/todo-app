<template>
    <div class="container-fluid mb-2">
        <h1 class="mt-3">Todo List</h1>
        <div class="row justify-content-center mt-4">
            <input v-model="todo.title" v-on:keyup.enter="saveTodo" class="mr-1 col-30p" placeholder="Add Todo"/>
            <button v-if="!todo.id" :disabled="!todo.title || !todo.title.length" @click="saveTodo" class="btn btn-primary">Save
            </button>
            <button v-if="todo.id && !todo.done" @click="updateTodo" class="btn btn-primary">Update</button>
            <button v-if="todo.id" @click="deleteTodo" class="btn btn-danger ml-1">Delete
            </button>
        </div>
        <div class="row">
            <div class="offset-md-3 col-md-6 mt-3">
                <VueNestable v-model="list" v-on:change="moveTodo">
                    <VueNestableHandle
                            slot-scope="{ item }"
                            :item="item"
                            class="d-flex">
                        <input type="checkbox" :disabled="item.done" :checked="item.done" v-on:change="doneTodo(item)"
                               class="ml-5 mr-1" placeholder="Done"/>
                        <small>Done</small>
                        <div v-on:click="todo = item" class="card-title col-70p">
                            {{ item.title }}
                        </div>
                        <small v-if="!Boolean(todo.done) && !todo.done_at" class="align-content-lg-end col-30p"> created
                            at: {{item.created_at}}
                        </small>
                        <small v-if="Boolean(todo.done) && todo.done_at" class="align-content-lg-end col-30p"> done at:
                            {{item.done_at}}
                        </small>
                    </VueNestableHandle>
                </VueNestable>
            </div>
        </div>

    </div>
</template>

<script>
    /* eslint-disable no-console */

    import { VueNestable, VueNestableHandle } from 'vue-nestable';
    import axios from 'axios';

    axios.defaults.baseURL = 'http://127.0.0.1:3000/api';
    //axios.defaults.headers.common['Access-Control-Allow-Origin'] = '*';
    export default {
        name      : 'TodoList',
        components: {
            VueNestable,
            VueNestableHandle,
        },
        mounted   : function () {
            this.getList();
        },
        data() {
            return {
                list: [],
                todo: {title: ''},
            };
        },
        props     : {
            msg: String,
        },
        methods   : {
            getList   : function () {
                axios.get('todo').then((res) => {
                    this.list = res.data;
                    console.log(res.data);
                });
            },
            saveTodo  : function () {
                if (!this.todo.done) {
                    axios.post('todo', this.todo).then((res) => {
                        this.list.push(res.data);
                        this.destroyTodo();
                    });
                }
            },
            moveTodo  : function (...event) {
                let [todo, {items, pathTo}] = event;
                console.log(todo, items, pathTo);
                axios.post('todo/sort', {items}).then((res) => {
                    console.log(res.data);
                });
            },
            updateTodo: function () {
                axios.put('todo/' + this.todo.id, this.todo).then(res => {
                    this.todo = res.data;
                    console.log(res.data);
                });
            },
            doneTodo  : function (todo) {
                axios.patch('todo/' + todo.id, {done: true}).catch(err => {
                    console.log(err);
                });
            },
            deleteTodo: function () {
                axios.delete('todo/' + this.todo.id).then(res => {
                    console.log(res.data);
                    this.destroyTodo();
                    this.getList();
                });
            },
            destroyTodo: function () {
                this.todo = {title: ''};
            }
        },
    };
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
    h3 {
        margin: 40px 0 0;
    }

    ul, ol {
        list-style-type: none;
        padding: 0;
    }

    li {
        display: inline-block;
        margin: 0 10px;
    }

    .nestable-list {
        display: block;
        position: relative;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .nestable-list .nestable-list {
        padding-left: 40px;
    }

    .nestable-item {
        display: block;
        position: relative;
        margin: 5px 0;
        padding: 10px 0;
        min-height: 20px;
        line-height: 20px;
        border: 1px solid #7c7981;
        border-radius: 5px;
    }

    .container {
        width: 100%;
        display: flex;
        padding-right: 15px;
        padding-left: 15px;
        margin-right: auto;
        margin-left: auto;
    }

    .col-50p {
        width: 50%;
    }

    .col-70p {
        width: 70%;
    }

    .col-30p {
        width: 30%;
    }

    a {
        color: #42b983;
    }

    .nestable {
        position: relative;
    }

    .nestable .nestable-list {
        margin: 0;
        padding: 0 0 0 40px;
        list-style-type: none;
    }

    .nestable > .nestable-list {
        padding: 0;
    }

    .nestable-item,
    .nestable-item-copy {
        margin: 10px 0 0;
    }

    .nestable-item:first-child,
    .nestable-item-copy:first-child {
        margin-top: 0;
    }

    .nestable-item .nestable-list,
    .nestable-item-copy .nestable-list {
        margin-top: 10px;
    }

    .nestable-item {
        position: relative;
    }

    .nestable-item.is-dragging .nestable-list {
        pointer-events: none;
    }

    .nestable-item.is-dragging * {
        opacity: 0;
        filter: alpha(opacity=0);
    }

    .nestable-item.is-dragging:before {
        content: " ";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(106, 127, 233, 0.274);
        border: 1px dashed #7c7981;
        -webkit-border-radius: 5px;
        border-radius: 5px;
    }

    .nestable-drag-layer {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 100;
        pointer-events: none;
        height: 40px;
    }

    .nestable-drag-layer > .nestable-list {
        position: absolute;
        top: 0;
        left: 0;
        padding: 0;
        background-color: rgba(106, 127, 233, 0.274);
    }

    .nestable [draggable="true"] {
        cursor: pointer;
    }

</style>
