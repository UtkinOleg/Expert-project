<script type="text/javascript" src="scripts/jquery.date_input.js"></script>
<script type="text/javascript">
jQuery.extend(DateInput.DEFAULT_OPTS, {
  month_names: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
  short_month_names: ["Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек"],
  short_day_names: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"]
});
$.extend(DateInput.DEFAULT_OPTS, { start_of_week: 1 });
$.extend(DateInput.DEFAULT_OPTS, {
  stringToDate: function(string) {
    var matches;
    if (matches = string.match(/^(\d{4,4})-(\d{2,2})-(\d{2,2})$/)) {
      return new Date(matches[3], matches[2] - 1, matches[1]);
    } else {
      return null;
    };
  },
  dateToString: function(date) {
    var month = (date.getMonth() + 1).toString();
    var dom = date.getDate().toString();
    if (month.length == 1) month = "0" + month;
    if (dom.length == 1) dom = "0" + dom;
    return dom + "." + month + "." + date.getFullYear();
  }
});
$($.date_input.initialize);
</script>

<?
//<script type="text/javascript">
// $(function() {
//  $("#my_date_input").date_input();
// });
//</script>

?>