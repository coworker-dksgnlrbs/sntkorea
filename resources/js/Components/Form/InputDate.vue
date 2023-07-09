<template>
    <div class="m-input-date type01">
        <input type="text" :id="id" :value="value">
    </div>
</template>
<script>
export default {
    props: {
        id: {
            default : "date"
        },
        minDate: {
            default: null
        },
        maxDate: {
            default: null
        },
        disableWeekOfDays:{
            default: () => {
                return [];
            }
        },
        disableDates: {
            default: () => {
                return [];
            }
        },
        value: {
            default: null
        }
    },

    methods: {
        disableDays(day){
            let month = day.getMonth();
            let date = day.getDate();
            let year = day.getFullYear();
            let formedDay = `${year}-${month+1}-${date}`;

            let isDisableWeekOfDay = this.disableWeekOfDays.includes(moment(formedDay).day());

            let isDisableDay = this.disableDates.some(disableDate => {
                return moment(formedDay).format("YY-MM-DD") === moment(disableDate).format("YY-MM-DD");
            });

            return [!isDisableDay && !isDisableWeekOfDay];
        }
    },

    mounted() {
        let self = this;

        $("#" + this.id).datepicker({
            dateFormat: "yy-mm-dd",
            firstDay: 1, // ,월요일부터 시작
            monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
            monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
            dayNames: ['일', '월', '화', '수', '목', '금', '토'],
            dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
            dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
            changeMonth: true, // 월선택 select box 표시 (기본은 false)
            changeYear: true, // 년선택 selectbox 표시 (기본은 false)
            showMonthAfterYear: true,  // 다음년도 월 보이기
            showOtherMonths: true, // 다른 월 달력에 보이기
            selectOtherMonths: true, // 다른 월 달력에 보이는거 클릭 가능하게 하기

            onSelect: function (date) {
                self.$emit('change', date);
            },
            minDate: self.minDate, // n일 이후부터 선택(0이면 오늘부터, 1이면 1일 뒤부터)
            maxDate: self.maxDate, // n일 이후까지만 선택(0이면 오늘날짜까지만, 1이면 1일 뒤까지만)
            beforeShowDay : self.disableDays
        });

        // 특정 요일 선택 막기
    }
}
</script>
