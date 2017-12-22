var rotationsTime = 8;//seconds
var wheelSpinTime = 6;//times
var ballSpinTime = 5;//times
var numorder = [0, 32, 15, 19, 4, 21, 2, 25, 17, 34, 6, 27, 13, 36, 11, 30, 8, 23, 10, 5, 24, 16, 33, 1, 20, 14, 31, 9, 22, 18, 29, 7, 28, 12, 35, 37, 3, 26];
var numred = [32, 19, 21, 25, 34, 27, 36, 30, 23, 5, 16, 1, 14, 9, 18, 7, 12, 26, 37];
var numblack = [0, 15, 4, 2, 17, 6, 13, 11, 8, 10, 24, 33, 20, 31, 22, 29, 28, 35, 3];

var numbg = $('.pieContainer');
var ballbg = $('.rte_ball');
var btnSpin = $('#btnSpin');
var convertToCash = $('.btnBuy');
var convertToPoints = $('.btnSell');
var toppart = $("#toppart");
var pfx = $.keyframe.getVendorPrefix();
var transform = pfx + 'transform';
var rinner = $("#rcircle");
var numberLoc = [];
var winningNum = 0;
var colorNum = 2;
var isSpinning = false;
var winSound = new Audio(basepath + 'public/audio/win.mp3');
var looseSound = new Audio(basepath + 'public/audio/loose.mp3');
var roulette = new Audio(basepath + 'public/audio/roulette.mp3');
var ball = new Audio(basepath + 'public/audio/roulette-ball.mp3');
createWheel();

function createWheel() {
    var temparc = 360 / numorder.length;
    for (var i = 0; i < numorder.length; i++) {
        numberLoc[numorder[i]] = [];
        numberLoc[numorder[i]][0] = i * temparc;
        numberLoc[numorder[i]][1] = (i * temparc) + temparc;

        newSlice = document.createElement("div");
        $(newSlice).addClass("hold");
        newHold = document.createElement("div");
        $(newHold).addClass("pie");
        newNumber = document.createElement("div");
        $(newNumber).addClass("num");

        newNumber.innerHTML = numorder[i];
        $(newSlice).attr('id', 'rSlice' + i);
        $(newSlice).css('transform', 'rotate(' + numberLoc[numorder[i]][0] + 'deg)');

        $(newHold).css('transform', 'rotate(9.73deg)');
        $(newHold).css('-webkit-transform', 'rotate(9.73deg)');

        if ($.inArray(numorder[i], numred) > -1) {
            $(newHold).addClass("redbg");
        } else if ($.inArray(numorder[i], numblack) > -1) {
            $(newHold).addClass("greybg");
        }

        $(newNumber).appendTo(newSlice);
        $(newHold).appendTo(newSlice);
        $(newSlice).appendTo(rinner);
    }
}

btnSpin.click(function () {
    roulette.play();
    roulette.pause();
    ball.play();
    ball.pause();
    winSound.play();
    winSound.pause();
    looseSound.play();
    looseSound.pause();
    if (isSpinning) {
        return;
    }
    var bid = parseInt($('#currentBitAmount span').html());
    if (!bid || bid <= 0) {
        swal({
            title: "No money",
            html: "<h3>You can convert your points into cash</h3>",
            showCancelButton: false,
            showConfirmButton: true
        });
    } else if (colorNum == 2) {
        swal({
            title: "Choose color!",
            html: "<h3>Choose a color before starting</h3>",
            showCancelButton: false,
            showConfirmButton: true
        });
    } else {
        isSpinning = true;
        $(".StartOptions").css("visibility", "hidden");
        winningNum = Math.floor((Math.random() * 34) + 0);
        spinTo(winningNum);
    }
});

convertToPoints.click(function () {
    var points = parseInt($('#totalPoints').html());
    var totalmoney = parseInt($('.totalmoney span').html());
    var totalmoneyFloor = Math.floor(totalmoney / 1000) * 1000;
    var moneyEnteredFloor = null;
    var newMoney = null;
    var newPoints = null;
    var pointsEncrease = null;
    if (totalmoney < 1000) {
        swal({
            title: 'You have not enough money',
            type: 'error',
            confirmButtonColor: '#30d674',
            showCancelButton: false,
            confirmButtonText: 'Ok'
        });
    }
    else {
        swal({
            title: 'Convert cash into points',
            input: 'number',
            inputAttributes: {
                min: 1000,
                max: totalmoneyFloor,
                value: 1000,
                step: 1000,
                onkeydown: "filterSwal(event)",
                "android:inputType": "numberDecimal",
                "android:digits": "0123456789",
                oncut: "return false",
                onpaste: "return false"
            },
            inputClass: 'swal2-input-class',
            showCancelButton: true,
            confirmButtonText: 'Convert',
            showLoaderOnConfirm: true,
            preConfirm: function (moneyEntered) {
                var val = parseInt($('.swal2-input-class').val());
                if (!val || val <= 0 || val > totalmoney) {
                    return new Promise(function (resolve, reject) {
                        reject('The value is not valid');
                    });
                }
                moneyEnteredFloor = Math.floor(moneyEntered / 1000) * 1000;
                pointsEncrease = moneyEnteredFloor / 1000;
                newMoney = totalmoney - moneyEnteredFloor;
                newPoints = points + pointsEncrease;
                return new Promise(function (resolve, reject) {
                    $.ajax({
                        type: 'POST',
                        url: '/games/roulette_update_data',
                        dataType: 'json',
                        data: {
                            points: newPoints,
                            money: newMoney
                        },
                        success: function (data) {
                            if (data.result == 'ok') {
                                resolve();
                            } else {
                                reject('Server error. Try again later please.');
                            }
                        },
                        error: function (data) {
                            reject('Server error. Try again later please.');
                        }
                    });
                });
            },
            allowOutsideClick: false
        }).then(function (moneyEntered) {
            swal({
                type: 'success',
                title: 'Conversion finished',
                html: 'You have converted $' + moneyEnteredFloor + ' into ' + pointsEncrease + ' points'
            });
            $('#totalPoints').html(newPoints);
            $('.totalmoney span').html(newMoney);
            var bid = $("#currentBitAmount span");
            if (parseInt(bid.html()) > newMoney) {
                bid.html(newMoney);
            }
        });
    }
});

convertToCash.click(function () {
    var points = parseInt($('#totalPoints').html());
    var totalmoney = parseInt($('.totalmoney span').html());
    var newMoney = null;
    var newPoints = null;
    var moneyEncrease = null;
    if (points < 1) {
        swal({
            title: 'You have no points',
            // text: "You get more points",
            type: 'error',
            showCancelButton: true,
            confirmButtonColor: '#30d674',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Get more points'
        }).then(function () {
            window.location.href = '/dashboard/wallet';
        });
    }
    else {
        swal({
            title: 'Convert points into cash',
            input: 'number',
            inputAttributes: {
                min: 1,
                max: points,
                value: 1,
                onkeydown: "filterSwal(event)",
                "android:inputType": "numberDecimal",
                "android:digits": "0123456789",
                oncut: "return false",
                onpaste: "return false"
            },
            inputClass: 'swal2-input-class',
            showCancelButton: true,
            confirmButtonText: 'Convert',
            showLoaderOnConfirm: true,
            preConfirm: function (pointsEntered) {
                var val = parseInt($('.swal2-input-class').val());
                if (!val || val <= 0 || val > points) {
                    return new Promise(function (resolve, reject) {
                        reject('The value is not valid');
                    });
                }
                moneyEncrease = 1000 * pointsEntered;
                newMoney = totalmoney + moneyEncrease;
                newPoints = points - pointsEntered;
                return new Promise(function (resolve, reject) {
                    $.ajax({
                        type: 'POST',
                        url: '/games/roulette_update_data',
                        dataType: 'json',
                        data: {
                            points: newPoints,
                            money: newMoney
                        },
                        success: function (data) {
                            if (data.result == 'ok') {
                                resolve();
                            } else {
                                reject('Server error. Try again later please.');
                            }
                        },
                        error: function (data) {
                            reject('Server error. Try again later please.');
                        }
                    });
                });
            },
            allowOutsideClick: false
        }).then(function (points) {
            swal({
                type: 'success',
                title: 'Conversion finished',
                html: 'You have converted ' + points + ' points into $' + moneyEncrease
            });
            $('#totalPoints').html(newPoints);
            $('.totalmoney span').html(newMoney);
        });
    }
});

function filterSwal(e) {
    //backspace, left, right, delete
    if (e.which == 8 || e.which == 37 || e.which == 38 || e.which == 46) {
        return;
    }
    if ((e.shiftKey || (e.which < 48 || e.which > 57)) && (e.which < 96 || e.which > 105)) {
        e.preventDefault();
    }
}

$('.RedCircle').click(function () {
    colorNum = 0;
    $(".BlackCircle").css("visibility", "hidden");
});
$('.BlackCircle').click(function () {
    colorNum = 1;
    $(".RedCircle").css("visibility", "hidden");
});

// $("input[type = 'radio'][name = 'startbet']").change(function () {
//     $(".betmoney").html(this.value);
// });

$('.place-bid-btn').click(function (e) {
    var self = $(e.currentTarget);
    var value = parseInt($(self.siblings('input')).val().trim());
    setBid(value);
});

$('.place-bid-input input').bind("cut copy paste", function (e) {
    e.preventDefault();
});

$(".place-bid-input input").on('keydown', function (e) {
    //backspace, left, right, delete
    if (e.which == 8 || e.which == 37 || e.which == 38 || e.which == 46) {
        return;
    }
    //enter
    if (e.which == 13) {
        e.preventDefault();
        var value = parseInt($(e.currentTarget).val().trim());
        setBid(value);
        return;
    }
    //digits (numpad also)
    if ((e.shiftKey || (e.which < 48 || e.which > 57)) && (e.which < 96 || e.which > 105)) {
        e.preventDefault();
    }
});

function setBid(value) {
    if (isSpinning) {
        return;
    }
    if (value && value > 0) {
        if (parseInt($($(".totalmoney span")[0]).html()) >= value) {
            $('#currentBitAmount span').html(value)
        } else {
            swal({
                title: "Not enough money",
                html: "<h3>Please insert proper value</h3>",
                showCancelButton: false,
                showConfirmButton: true
            });
        }
    } else {
        swal({
            title: "Invalid number",
            html: "<h3>Please insert proper value</h3>",
            showCancelButton: false,
            showConfirmButton: true
        });
    }
}

function resetAni() {
    animationPlayState = "animation-play-state";
    playStateRunning = "running";

    $(ballbg).css(pfx + animationPlayState, playStateRunning).css(pfx + "animation", "none");

    $(numbg).css(pfx + animationPlayState, playStateRunning).css(pfx + "animation", "none");
    $(toppart).css(pfx + animationPlayState, playStateRunning).css(pfx + "animation", "none");

    $("#rotate2").html("");
    $("#rotate").html("");
}

function spinTo(num) {
    //get location
    var temp = numberLoc[num][0] + 4;

    //randomize
    var rndSpace = Math.floor((Math.random() * 360) + 1);

    resetAni();
    setTimeout(function () {
        // setTimeout(function () {
        //     roulette.pause();
        //     roulette.currentTime = 0;
        //     ball.play();
        // }, 6000);
        // roulette.play();
        bgrotateTo(rndSpace);
        ballrotateTo(rndSpace + temp);
    }, 200);
}

function Win() {
    winSound.play();
    var totalmoney = $(".totalmoney span");
    var bid = $("#currentBitAmount span");
    totalmoney.html(parseInt(totalmoney.html()) + parseInt(bid.html()));
    swal({
        title: "You win!",
        html: "<h3>You earned $" + bid.html(),
        showCancelButton: false,
        showConfirmButton: true
    });
}

function Lost() {
    looseSound.play();
    var totalmoney = $(".totalmoney span");
    var bid = $("#currentBitAmount span");
    totalmoney.html(parseInt(totalmoney.html()) - parseInt(bid.html()));
    swal({
        title: "You lost!",
        html: "<h3>You lost $" + bid.html(),
        showCancelButton: false,
        showConfirmButton: true
    });
    if (parseInt(totalmoney.html()) < parseInt(bid.html())) {
        bid.html(totalmoney.html());
    }
}

function finishSpin() {
    var type = 'win';
    if (colorNum == 0 && $.inArray(winningNum, numred) > -1) {
        Win();
    }
    else if (colorNum == 1 && $.inArray(winningNum, numblack) > -1) {
        Win();
    }
    else {
        Lost();
        type = 'lost';
    }

    $.ajax({
        type: 'POST',
        url: '/games/roulette_update_data',
        dataType: 'json',
        data: {
            money: $(".totalmoney span").html(),
            type: type
        },
        success: function (data) {
            if (data.result == 'ok') {
            } else {
            }
        },
        error: function (data) {
        }
    });

    $(".BlackCircle").css("visibility", "visible");
    $(".RedCircle").css("visibility", "visible");
    colorNum = 2;
    isSpinning = false;
}

function ballrotateTo(deg) {
    var temptime = (rotationsTime * 1000);
    var dest = (-360 * ballSpinTime) - (360 - deg);

    $.keyframe.define({
        name: 'rotate2',
        from: {
            transform: 'rotate(0deg)'
        },
        to: {
            transform: 'rotate(' + dest + 'deg)'
        }
    });

    $(ballbg).playKeyframe({
        name: 'rotate2', // name of the keyframe you want to bind to the selected element
        duration: temptime, // [optional, default: 0, in ms] how long you want it to last in milliseconds
        timingFunction: 'ease-in-out', // [optional, default: ease] specifies the speed curve of the animation
        complete: function () {
            finishSpin();
        } //[optional]  Function fired after the animation is complete. If repeat is infinite, the function will be fired every time the animation is restarted.
    });
}

function bgrotateTo(deg) {
    var dest = 360 * wheelSpinTime + deg;
    var temptime = (rotationsTime * 1000) - 1000;

    $.keyframe.define({
        name: 'rotate',
        from: {
            transform: 'rotate(0deg)'
        },
        to: {
            transform: 'rotate(' + dest + 'deg)'
        }
    });

    $(numbg).playKeyframe({
        name: 'rotate', // name of the keyframe you want to bind to the selected element
        duration: temptime, // [optional, default: 0, in ms] how long you want it to last in milliseconds
        timingFunction: 'ease-in-out', // [optional, default: ease] specifies the speed curve of the animation
        complete: function () {

        } //[optional]  Function fired after the animation is complete. If repeat is infinite, the function will be fired every time the animation is restarted.
    });

    $(toppart).playKeyframe({
        name: 'rotate', // name of the keyframe you want to bind to the selected element
        duration: temptime, // [optional, default: 0, in ms] how long you want it to last in milliseconds
        timingFunction: 'ease-in-out', // [optional, default: ease] specifies the speed curve of the animation
        complete: function () {

        } //[optional]  Function fired after the animation is complete. If repeat is infinite, the function will be fired every time the animation is restarted.
    });
}