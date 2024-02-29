<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Icon from '@/Icons/Icon.vue'
import {Link} from '@inertiajs/vue3'
export default {
    props: {

    },
    components: {
        AuthenticatedLayout,
        Icon,
        Link,
    },
    data() {
        return {
            searchTerm : '',
            searchResults : [],
            searchType : 'article'
        }
    },
    mounted() {

    },
    methods: {
        search() {
            if (this.searchTerm.length < 3) return; 
            if(this.searchType === 'article'){
                try {
                    axios.post('/searchArtResult', {
                        'term' : this.searchTerm,
                    }).then((resp) => {
                        this.searchResults = resp.data;
                    })
                } catch (error) {
                    console.error(error);
                }
            }else if(this.searchType === 'boutique'){
                try {
                    axios.post('/searchBoutResult', {
                        'term' : this.searchTerm,
                    }).then((resp) => {
                        this.searchResults = resp.data;
                    })
                } catch (error) {
                    console.error(error);
                }
            }
        },
        getPath(type, name) {
            if (type === 'boutique') {
                return new URL("../../../public/boutique_medias/" + name, import.meta.url).href;
            } else if (type === 'article') {
                return new URL("../../../public/article_medias/" + name, import.meta.url).href
            }
        },
    }
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="relative">
            <input class="w-full create font-bold" placeholder="Rechercher" v-model="searchTerm" @input="search()" />
            <button class="absolute top-0 right-0">
                <Icon :name="'loupe'" class="w-8 h-8 fill-primary"></Icon>
            </button>
        </div>
        <div class="mt-4 ml-2 flex gap-4">
            <button @click="searchType = 'article'; search()" :class="searchType === 'article' ? 'text-white bg-secondary' : 'text-secondary border border-secondary'" class="px-4 py-2 rounded-full text-xl">Articles </button>
            <button @click="searchType = 'boutique'; search()" :class="searchType === 'boutique' ? 'text-white bg-secondary' : 'text-secondary border border-secondary'" class="px-4 py-2 rounded-full text-xl">Boutiques</button>
        </div>

        <div>
            <article v-for="art,i in searchResults" v-if="searchType === 'article'" class="px-4 my-4 flex gap-2">
                <img :src="getPath(searchType, art.article_media.path)" alt="" class="w-20 h-20 rounded-full">
                <div>
                    
                    <p class="font-economica text-3xl text-black font-bold">{{ art.name }}</p>
                    <span>{{ art.category }}</span>
                </div>
                <Link :href="'/view/article/' + art.id">
                    Voir plus
                </Link>
            </article>
            <article v-for="bout,i in searchResults" v-if="searchType === 'boutique'" class="px-4 my-4 flex gap-2">
                <img v-if="bout.image" :src="getPath(searchType, bout.image.path)" alt="" class="w-20 h-20 rounded-full">
                <div>
                    
                    <p class="font-economica text-3xl text-black font-bold">{{ bout.name }}</p>
                    <span>{{ bout.category }}</span>
                </div>
                <Link :href="'/visit/shop/' + bout.name">
                    Voir plus
                </Link>
            </article>
        </div>
    </AuthenticatedLayout></template>