<template>
    <Head>
        <title>{{ $t('campaigns.settings.title') }}</title>
    </Head>
    <Authenticated>
        <section class="card-md card-dialog">
            <h1 class="card-title-d mb-6">{{ $t('campaigns.settings.header') }}</h1>

            <article v-for="campaign in campaigns"
                     :key="campaign.id"
                     class="grid items-center md:grid-cols-8 grid-cols-6 gap-y-2 rounded-md border-gray-100 border px-4 py-5 mb-4"
            >
                <div class="md:col-span-1 w-full">
                    <div class="md:float-none float-right">
                        <Link
                            :title="$t('campaigns.settings.buttons.edit')"
                            class="hover:bg-gray-50 p-1"
                            :href="route('campaign.edit', campaign.id)"
                        ><img src="/icons/edit.svg" class="inline-block" :alt="$t('campaigns.settings.buttons.edit')">
                        </Link>
                        <Link
                            :title="$t('campaigns.settings.buttons.delete')"
                            class="hover:bg-gray-50 p-1"
                            :href="route('campaign.delete', campaign.id)"
                        ><img src="/icons/delete.svg" class="inline-block" :alt="$t('campaigns.settings.buttons.delete')">
                        </Link>
                    </div>
                </div>

                <h3 class="card-title col-span-3">{{ campaign.name }}</h3>

                <div class="pr-2 col-span-2 flex flex-row flex-wrap md:justify-start justify-end">
                    <div v-for="sourceType in campaign.sources">
                        <SourceIcon :source-type="sourceType" class="w-5 mr-2"/>
                    </div>
                </div>

                <div class="md:pr-2 md:col-span-2 col-span-6 justify-self-end w-full">
                    <Link
                        class="btn btn-sm btn-outline-primary md:w-auto w-full"
                        :href="route('campaign.source', campaign.id)"
                    >{{ $t('campaigns.settings.buttons.sources') }}
                    </Link>
                </div>
            </article>

            <article v-if="!campaigns || campaigns.length === 0">
                {{ $t('campaigns.settings.emptyText') }}
            </article>

            <div class="flex flex-row-reverse align-middle">
                <Link
                    class="btn btn-md btn-outline-primary"
                    :href="route('campaign.create')"
                >{{ $t('campaigns.settings.buttons.create') }}
                </Link>
            </div>
        </section>
    </Authenticated>
</template>

<script>
import Authenticated from "@/Layouts/Authenticated.vue";
import SourceIcon from "@/Pages/Sources/Components/SourceIcon.vue";
import {Head, Link} from '@inertiajs/vue3';

export default {
    components: {SourceIcon, Authenticated, Head, Link},
    props: {
        sources: Array,
        campaigns: Object
    },
}
</script>
