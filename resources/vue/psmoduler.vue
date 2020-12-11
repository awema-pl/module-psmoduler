<template>
<div>
    <p>Translation key <code>PSMODULER_EXAMPLE</code> from <code>psmoduler/resources/lang/**/js.php</code>: {{$lang.PSMODULER_EXAMPLE}}</p>
    <button class="form-builder__send btn" @click="testDebug">Test console log for debug</button>
    <p>From config JS file: {{this.example_data}}</p>
    <p>Example function: {{this.exampleFromFunction}}</p>
    <p>
        <button class="form-builder__send btn" @click="testLoading">Test loading</button>
        <span v-if="isLoading">is loading...</span>
    </p>
</div>
</template>

<script>
import psmodulerMixin from '../js/mixins/psmoduler'
import {consoleDebug} from '../js/modules/helpers'

let _uniqSectionId = 0;

export default {

    name: 'psmoduler',

    mixins: [ psmodulerMixin ],

    props: {
        name: {
            type: String,
            default() {
                return `psmoduler-${ _uniqSectionId++ }`
            }
        },

        default: Object,

        storeData: String,
    },


    computed: {
        psmoduler() {
            return this.$store.state.psmoduler[this.name]
        },

        isLoading() {
            return this.psmoduler && this.psmoduler.isLoading
        },
    },

    created() {

        let data = this.storeData ? this.$store.state[this.storeData] : (this.default || {})

        this.$store.commit('psmoduler/create', {
            name: this.name,
            data
        })
    },

    mounted() {

    },

    methods: {
        testDebug(){
            consoleDebug('message', ['data1'], ['data2'])
        },

        testLoading(){
            if ( this.isLoading) return;

            AWEMA.emit(`psmoduler::${this.name}:before-test-loading`)

            this.$store.dispatch('psmoduler/testLoading', {
                name: this.name
            }).then( data => {
                consoleDebug('data', data);
                this.$emit('success', data.data)
                this.$store.$set(this.name, this.$get(data, 'data', {}))
            })
        }
    },


    beforeDestroy() {

    }
}
</script>
