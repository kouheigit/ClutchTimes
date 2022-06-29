function checkAnswer (radio) {
    var rd_value = radio.value;
    var q2 = document.getElementById('q2');
    var q2_1 = document.getElementById('q2-1');
    var q2_2 = document.getElementById('q2-2');
    if (rd_value == 'reserve-non') {
        q2_1.checked = false;
        q2_2.checked = false;
        q2_1.disabled = true;
        q2_2.disabled = true;
        q2.style.color = '#999999';
    } else {
        q2.style.color = '#000000';
        q2_1.disabled = false;
        q2_2.disabled = false;
    }
}
