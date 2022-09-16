@extends('default')

@section('app')
<div id="app">
  <v-card>
    Hello From Laravel and Vue
  </v-card>           
</div>
@endsection

@section('vue')
    <script>
        new Vue({
            el: '#app',
            vuetify: new Vuetify(),
            data: () => ({

            }),
            mounted() {
                
            },
            methods: {

            }                
        });
    </script>
@show
