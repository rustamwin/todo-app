<template>
    <div id="app">
        <TodoList msg="Welcome to Todo App"/>
    </div>
</template>

<script>
    import TodoList from './components/TodoList.vue';
    import 'bootstrap/dist/css/bootstrap.css';
    import 'bootstrap-vue/dist/bootstrap-vue.css';

    export default {
        name      : 'app',
        components: {
            TodoList,
        }
    };
</script>

<style>
    #app {
        font-family: 'Avenir', Helvetica, Arial, sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-align: center;
        color: #2c3e50;
        margin-top: 60px;
    }
</style>
