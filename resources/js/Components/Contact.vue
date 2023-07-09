<template>
    <div class="contact_box">
        <div class="container3">
            <h3>contact</h3>
            <ul>
                <li><a href="/qnas/create">문의하기</a></li>
                <li><a href="/surveys/create" @click.prevent="ready">1분 문답 시작하기</a></li>
                <!-- <li><a href="">유지보수</a></li> -->
            </ul>
        </div>
    </div>

</template>
<script>
export default {
    methods: {
        ready(){
            return alert("준비중입니다.");
        }
    }
}
</script>
