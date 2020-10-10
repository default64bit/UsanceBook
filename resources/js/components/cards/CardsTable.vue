<template>
    <div class="data_table cards_table">
        <div class="toolbar">
            <search v-model="search" placeholder="Search Cards" @search="searchInCards()"></search>
            <button class="btn" @click="addClicked()">Add New Card</button>
        </div>
        <hr>
        <table>
            <tbody v-if="cards.length>0">
                <tr v-for="(card,i) in cards" :key="i" :class="['card_style'+((i%4)+1)]">
                    <td class="mr-0 ml-auto text-yellow-800"><strong>{{card.bank}}</strong></td>
                    <td><img src="/assets/sim.svg" alt=""></td>
                    <td class="py-4 Roboto text-center" name="number">{{card.number}}</td>
                    <td class="mb-0 mt-auto">{{card.user.name}} {{card.user.family}}</td>
                    <td class="actions">
                        <i class="fas fa-list" @mouseup="toggle_menu(i,true)"></i>
                        <ul :open="card.menu" @mouseenter="card.can_close=false" @mouseleave="card.can_close=true">
                            <li><button class="btn" @click="editClicked(i)"><i class="far fa-pencil-alt mr-2"></i> Edit</button></li>
                            <li><button class="btn" @click="deleteClicked(i)"><i class="far fa-trash-alt mr-2"></i> Delete</button></li>
                            <li><button class="btn" @click="goToTransactions(i)"><i class="far fa-newspaper mr-2"></i> Transactions</button></li>
                            <li><button class="btn"><i class="far fa-chart-line mr-2"></i> Statistics</button></li>
                        </ul>
                    </td>
                </tr>
            </tbody>
            <div class="my-4 text-center" v-else>No Data</div>
        </table>
        <hr class="mb-4">
        <div class="loading flex flex-col justify-center items-center" v-if="pagination.next">
            <button class="btn bg-gray-700" @click="getCards(pagination.next,false)" v-if="!loading">Load More</button>
            <span class="text-5xl fad fa-spinner fa-spin" v-else></span>
        </div>
    </div>
</template>

<script>
    import token from '../../auth/token'
    import Search from '../layouts/form/Search'

    export default {
        name: 'CardsTable',
        components: {
            'search': Search
        },
        data(){
            return {
                cards: [],
                pagination: {},

                search: '',

                loading: false,
                access_token: '',
            }
        },
        created(){
            this.$parent.$on('new_value',(value)=>{
                this.cards.unshift(value);
            });
            this.$parent.$on('edit_value',(value)=>{
                this.$set(this.cards,value.index,value.data);
            });
            this.$parent.$on('delete_value',(value)=>{
                this.cards.splice(value.index,1);
            });
        },
        mounted(){
            document.getElementById('app').addEventListener('mousedown',()=>{
                for(let i=0; i<this.cards.length; i++){
                    if(this.cards[i].can_close){
                        this.$set(this.cards[i],'menu',false);
                    }
                }
            });

            this.getCards();
        },
        methods: {
            toggle_menu(index,state){
                this.$set(this.cards[index],'menu',state);
                this.$set(this.cards[index],'can_close',state);
            },

            async getCards(url = '/api/v1/cards', first_time = true){
                if(!url){ return 0; }

                if(!first_time){
                    if(this.search != null || this.search != undefined){
                        url += `&search=${this.search}`;
                    }
                }

                await token.getToken().then((value)=>{ this.access_token = value; });

                this.loading = true;
                axios({
                    url: url,
                    method: 'get',
                    headers: {
                        'Authorization': `Bearer ${this.access_token}`,
                        'Content-Type': 'application/json'
                    },
                }).then(response=>{
                    if(first_time){
                        this.cards = response.data.data;
                    }else{
                        this.cards.push(...response.data.data);
                    }
                    this.pagination = {
                        current_page:response.data.meta.current_page,
                        first:response.data.links.first,
                        last:response.data.links.last,
                        next:response.data.links.next,
                        prev:response.data.links.prev,
                    }
                    this.loading = false;
                }).catch(error=>{
                    this.loading = false;
                });
            },

            searchInCards(){
                let url = '/api/v1/cards';
                if(this.search != null || this.search != undefined){
                    url += `?search=${this.search}`;
                }
                this.getCards(url);
            },

            addClicked(){
                this.$emit('add');
            },
            editClicked(index){
                this.$emit('edit',{
                    index: index,
                    data: this.cards[index],
                });
                this.toggle_menu(index,false);
            },
            deleteClicked(index){
                this.$emit('delete',{
                    index: index,
                    data: this.cards[index],
                });
                this.toggle_menu(index,false);
            },

            goToTransactions(index){
                let id = this.cards[index].id;
                this.$router.push(`/Transactions?card=${id}`);
            },

        },
    }
</script>

<style>

</style>