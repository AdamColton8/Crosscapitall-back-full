console.log('hello from script.js');
$(function () {
  $('.user_setting').popup({
    on:'click',
    position: 'bottom right'
  });

  $('.statistic_acordion').accordion({
    exclusive: false
  });
  $('.user__referrals').accordion({
    exclusive: false
  });
});