<template>
    <div class="items type03">
        <div class="item" v-for="item in items" :key="item.id">
            <span class="date">{{ item.created_at }}</span>

            <div class="content">
                <h3 class="title">
                    {{item.worker ? `전문가 "${item.worker.name}"님께 보낸 견적요청서` : "업체에 보낸 견적요청서"}}
                </h3>

                <div class="infos">
                    <div class="info">
                        <p class="info-title">행사유형 : </p>
                        <p class="info-body">{{ item.category }}</p>
                    </div>

                    <div class="info">
                        <p class="info-title">필요시간 : </p>
                        <p class="info-body">{{ item.time }}</p>
                    </div>

                    <div class="info">
                        <p class="info-title">장소 : </p>
                        <p class="info-body">{{ item.address }}</p>
                    </div>

                    <div class="info">
                        <p class="info-title">선호스타일 : </p>
                        <p class="info-body">{{ item.style }}</p>
                    </div>

                    <div class="info">
                        <p class="info-title">필요시간 : </p>
                        <p class="info-body">{{ item.required_at }}</p>
                    </div>

                    <div class="info">
                        <p class="info-title">희망금액 : </p>
                        <p class="info-body">{{ item.price }}원</p>
                    </div>

                    <div class="info">
                        <p class="info-title">추가내용 : </p>
                        <p class="info-body">{{ item.comment }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import {Link} from '@inertiajs/inertia-vue';

    export default {
        components: {Link},
        props: ["items"],
    }
</script>
