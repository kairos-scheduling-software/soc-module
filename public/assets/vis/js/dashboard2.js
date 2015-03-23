var dashboard2 = (function () {

    "use strict";

    var chart1;
    var chart2;
    var chart3;
    var chart4;
    var chart5;

    var data =
        [
            {
                "day": 1,
                "hour": 1,
                "value": 16
            },
            {
                "day": 1,
                "hour": 1.5,
                "value": 16
            },
            {
                "day": 1,
                "hour": 2,
                "value": 20
            },
            {
                "day": 1,
                "hour": 2.5,
                "value": 0
            },
            {
                "day": 1,
                "hour": 3,
                "value": 0
            },
            {
                "day": 1,
                "hour": 3.5,
                "value": 0
            },
            {
                "day": 1,
                "hour": 4,
                "value": 2
            },
            {
                "day": 1,
                "hour": 4.5,
                "value": 0
            },
            {
                "day": 1,
                "hour": 5,
                "value": 9
            },
            {
                "day": 1,
                "hour": 5.5,
                "value": 25
            },
            {
                "day": 1,
                "hour": 6,
                "value": 49
            },
            {
                "day": 1,
                "hour": 6.5,
                "value": 57
            },
            {
                "day": 1,
                "hour": 7,
                "value": 61
            },
            {
                "day": 1,
                "hour": 7.5,
                "value": 37
            },
            {
                "day": 1,
                "hour": 8,
                "value": 66
            },
            {
                "day": 1,
                "hour": 8.5,
                "value": 70
            },
            {
                "day": 1,
                "hour": 9,
                "value": 55
            },
            {
                "day": 1,
                "hour": 9.5,
                "value": 51
            },
            {
                "day": 1,
                "hour": 10,
                "value": 55
            },
            {
                "day": 1,
                "hour": 10.5,
                "value": 17
            },
            {
                "day": 1,
                "hour": 11,
                "value": 20
            },
            {
                "day": 1,
                "hour": 11.5,
                "value": 9
            },
            {
                "day": 1,
                "hour": 12,
                "value": 4
            },
            {
                "day": 1,
                "hour": 12.5,
                "value": 0
            },
            {
                "day": 1,
                "hour": 13,
                "value": 12
            },
            {
                "day": 1,
                "hour": 13.5,
                "value": 6
            },
            {
                "day": 1,
                "hour": 14,
                "value": 2
            },
            {
                "day": 1,
                "hour": 14.5,
                "value": 0
            },
            {
                "day": 1,
                "hour": 15,
                "value": 0
            },
            {
                "day": 1,
                "hour": 15.5,
                "value": 0
            },
            {
                "day": 1,
                "hour": 16,
                "value": 2
            },
            {
                "day": 1,
                "hour": 16.5,
                "value": 4
            },
            {
                "day": 1,
                "hour": 17,
                "value": 11
            },
            {
                "day": 1,
                "hour": 17.5,
                "value": 28
            },
            {
                "day": 1,
                "hour": 18,
                "value": 49
            },
            {
                "day": 1,
                "hour": 18.5,
                "value": 51
            },
            {
                "day": 1,
                "hour": 19,
                "value": 47
            },
            {
                "day": 1,
                "hour": 19.5,
                "value": 38
            },
            {
                "day": 1,
                "hour": 20,
                "value": 65
            },
            {
                "day": 1,
                "hour": 20.5,
                "value": 60
            },
            {
                "day": 1,
                "hour": 21,
                "value": 50
            },
            {
                "day": 1,
                "hour": 21.5,
                "value": 65
            },
            {
                "day": 1,
                "hour": 22,
                "value": 50
            },
            {
                "day": 1,
                "hour": 22.5,
                "value": 22
            },
            {
                "day": 1,
                "hour": 23,
                "value": 11
            },
            {
                "day": 1,
                "hour": 23.5,
                "value": 12
            },
            {
                "day": 1,
                "hour": 24,
                "value": 9
            },
            {
                "day": 2,
                "hour": 1,
                "value": 0
            },
            {
                "day": 2,
                "hour": 1.5,
                "value": 13
            },
            {
                "day": 2,
                "hour": 2,
                "value": 5
            },
            {
                "day": 2,
                "hour": 2.5,
                "value": 8
            },
            {
                "day": 2,
                "hour": 3,
                "value": 8
            },
            {
                "day": 2,
                "hour": 3.5,
                "value": 0
            },
            {
                "day": 2,
                "hour": 4,
                "value": 0
            },
            {
                "day": 2,
                "hour": 4.5,
                "value": 2
            },
            {
                "day": 2,
                "hour": 5,
                "value": 5
            },
            {
                "day": 2,
                "hour": 5.5,
                "value": 12
            },
            {
                "day": 2,
                "hour": 6,
                "value": 34
            },
            {
                "day": 2,
                "hour": 6.5,
                "value": 43
            },
            {
                "day": 2,
                "hour": 7,
                "value": 54
            },
            {
                "day": 2,
                "hour": 7.5,
                "value": 44
            },
            {
                "day": 2,
                "hour": 8,
                "value": 40
            },
            {
                "day": 2,
                "hour": 8.5,
                "value": 48
            },
            {
                "day": 2,
                "hour": 9,
                "value": 54
            },
            {
                "day": 2,
                "hour": 9.5,
                "value": 59
            },
            {
                "day": 2,
                "hour": 10,
                "value": 60
            },
            {
                "day": 2,
                "hour": 10.5,
                "value": 51
            },
            {
                "day": 2,
                "hour": 11,
                "value": 21
            },
            {
                "day": 2,
                "hour": 11.5,
                "value": 16
            },
            {
                "day": 2,
                "hour": 12,
                "value": 9
            },
            {
                "day": 2,
                "hour": 12.5,
                "value": 5
            },
            {
                "day": 2,
                "hour": 13,
                "value": 4
            },
            {
                "day": 2,
                "hour": 13.5,
                "value": 7
            },
            {
                "day": 2,
                "hour": 14,
                "value": 0
            },
            {
                "day": 2,
                "hour": 14.5,
                "value": 0
            },
            {
                "day": 2,
                "hour": 15,
                "value": 0
            },
            {
                "day": 2,
                "hour": 15.5,
                "value": 0
            },
            {
                "day": 2,
                "hour": 16,
                "value": 0
            },
            {
                "day": 2,
                "hour": 16.5,
                "value": 2
            },
            {
                "day": 2,
                "hour": 17,
                "value": 4
            },
            {
                "day": 2,
                "hour": 17.5,
                "value": 13
            },
            {
                "day": 2,
                "hour": 18,
                "value": 26
            },
            {
                "day": 2,
                "hour": 18.5,
                "value": 58
            },
            {
                "day": 2,
                "hour": 19,
                "value": 61
            },
            {
                "day": 2,
                "hour": 19.5,
                "value": 59
            },
            {
                "day": 2,
                "hour": 20,
                "value": 53
            },
            {
                "day": 2,
                "hour": 20.5,
                "value": 54
            },
            {
                "day": 2,
                "hour": 21,
                "value": 64
            },
            {
                "day": 2,
                "hour": 21.5,
                "value": 55
            },
            {
                "day": 2,
                "hour": 22,
                "value": 52
            },
            {
                "day": 2,
                "hour": 22.5,
                "value": 53
            },
            {
                "day": 2,
                "hour": 23,
                "value": 18
            },
            {
                "day": 2,
                "hour": 23.5,
                "value": 3
            },
            {
                "day": 2,
                "hour": 24,
                "value": 9
            },
            {
                "day": 3,
                "hour": 1,
                "value": 12
            },
            {
                "day": 3,
                "hour": 1.5,
                "value": 2
            },
            {
                "day": 3,
                "hour": 2,
                "value": 8
            },
            {
                "day": 3,
                "hour": 2.5,
                "value": 2
            },
            {
                "day": 3,
                "hour": 3,
                "value": 0
            },
            {
                "day": 3,
                "hour": 3.5,
                "value": 8
            },
            {
                "day": 3,
                "hour": 4,
                "value": 2
            },
            {
                "day": 3,
                "hour": 4.5,
                "value": 0
            },
            {
                "day": 3,
                "hour": 5,
                "value": 2
            },
            {
                "day": 3,
                "hour": 5.5,
                "value": 4
            },
            {
                "day": 3,
                "hour": 6,
                "value": 14
            },
            {
                "day": 3,
                "hour": 6.5,
                "value": 31
            },
            {
                "day": 3,
                "hour": 7,
                "value": 48
            },
            {
                "day": 3,
                "hour": 7.5,
                "value": 46
            },
            {
                "day": 3,
                "hour": 8,
                "value": 50
            },
            {
                "day": 3,
                "hour": 8.5,
                "value": 66
            },
            {
                "day": 3,
                "hour": 9,
                "value": 54
            },
            {
                "day": 3,
                "hour": 9.5,
                "value": 56
            },
            {
                "day": 3,
                "hour": 10,
                "value": 67
            },
            {
                "day": 3,
                "hour": 10.5,
                "value": 54
            },
            {
                "day": 3,
                "hour": 11,
                "value": 23
            },
            {
                "day": 3,
                "hour": 11.5,
                "value": 14
            },
            {
                "day": 3,
                "hour": 12,
                "value": 6
            },
            {
                "day": 3,
                "hour": 12.5,
                "value": 8
            },
            {
                "day": 3,
                "hour": 13,
                "value": 7
            },
            {
                "day": 3,
                "hour": 13.5,
                "value": 0
            },
            {
                "day": 3,
                "hour": 14,
                "value": 8
            },
            {
                "day": 3,
                "hour": 14.5,
                "value": 2
            },
            {
                "day": 3,
                "hour": 15,
                "value": 0
            },
            {
                "day": 3,
                "hour": 15.5,
                "value": 2
            },
            {
                "day": 3,
                "hour": 16,
                "value": 0
            },
            {
                "day": 3,
                "hour": 16.5,
                "value": 0
            },
            {
                "day": 3,
                "hour": 17,
                "value": 0
            },
            {
                "day": 3,
                "hour": 17.5,
                "value": 4
            },
            {
                "day": 3,
                "hour": 18,
                "value": 8
            },
            {
                "day": 3,
                "hour": 18.5,
                "value": 8
            },
            {
                "day": 3,
                "hour": 19,
                "value": 6
            },
            {
                "day": 3,
                "hour": 19.5,
                "value": 14
            },
            {
                "day": 3,
                "hour": 20,
                "value": 12
            },
            {
                "day": 3,
                "hour": 20.5,
                "value": 9
            },
            {
                "day": 3,
                "hour": 21,
                "value": 14
            },
            {
                "day": 3,
                "hour": 21.5,
                "value": 0
            },
            {
                "day": 3,
                "hour": 22,
                "value": 4
            },
            {
                "day": 3,
                "hour": 22.5,
                "value": 7
            },
            {
                "day": 3,
                "hour": 23,
                "value": 6
            },
            {
                "day": 3,
                "hour": 23.5,
                "value": 0
            },
            {
                "day": 3,
                "hour": 24,
                "value": 0
            },
            {
                "day": 4,
                "hour": 1,
                "value": 0
            },
            {
                "day": 4,
                "hour": 1.5,
                "value": 0
            },
            {
                "day": 4,
                "hour": 2,
                "value": 0
            },
            {
                "day": 4,
                "hour": 2.5,
                "value": 0
            },
            {
                "day": 4,
                "hour": 3,
                "value": 7
            },
            {
                "day": 4,
                "hour": 3.5,
                "value": 6
            },
            {
                "day": 4,
                "hour": 4,
                "value": 0
            },
            {
                "day": 4,
                "hour": 4.5,
                "value": 0
            },
            {
                "day": 4,
                "hour": 5,
                "value": 0
            },
            {
                "day": 4,
                "hour": 5.5,
                "value": 0
            },
            {
                "day": 4,
                "hour": 6,
                "value": 0
            },
            {
                "day": 4,
                "hour": 6.5,
                "value": 0
            },
            {
                "day": 4,
                "hour": 7,
                "value": 0
            },
            {
                "day": 4,
                "hour": 7.5,
                "value": 0
            },
            {
                "day": 4,
                "hour": 8,
                "value": 2
            },
            {
                "day": 4,
                "hour": 8.5,
                "value": 2
            },
            {
                "day": 4,
                "hour": 9,
                "value": 5
            },
            {
                "day": 4,
                "hour": 9.5,
                "value": 6
            },
            {
                "day": 4,
                "hour": 10,
                "value": 0
            },
            {
                "day": 4,
                "hour": 10.5,
                "value": 4
            },
            {
                "day": 4,
                "hour": 11,
                "value": 0
            },
            {
                "day": 4,
                "hour": 11.5,
                "value": 2
            },
            {
                "day": 4,
                "hour": 12,
                "value": 10
            },
            {
                "day": 4,
                "hour": 12.5,
                "value": 7
            },
            {
                "day": 4,
                "hour": 13,
                "value": 0
            },
            {
                "day": 4,
                "hour": 13.5,
                "value": 19
            },
            {
                "day": 4,
                "hour": 14,
                "value": 9
            },
            {
                "day": 4,
                "hour": 14.5,
                "value": 4
            },
            {
                "day": 4,
                "hour": 15,
                "value": 16
            },
            {
                "day": 4,
                "hour": 15.5,
                "value": 16
            },
            {
                "day": 4,
                "hour": 16,
                "value": 20
            },
            {
                "day": 4,
                "hour": 16.5,
                "value": 0
            },
            {
                "day": 4,
                "hour": 17,
                "value": 0
            },
            {
                "day": 4,
                "hour": 17.5,
                "value": 0
            },
            {
                "day": 4,
                "hour": 18,
                "value": 2
            },
            {
                "day": 4,
                "hour": 18.5,
                "value": 0
            },
            {
                "day": 4,
                "hour": 19,
                "value": 9
            },
            {
                "day": 4,
                "hour": 19.5,
                "value": 25
            },
            {
                "day": 4,
                "hour": 20,
                "value": 49
            },
            {
                "day": 4,
                "hour": 20.5,
                "value": 57
            },
            {
                "day": 4,
                "hour": 21,
                "value": 61
            },
            {
                "day": 4,
                "hour": 21.5,
                "value": 37
            },
            {
                "day": 4,
                "hour": 22,
                "value": 66
            },
            {
                "day": 4,
                "hour": 22.5,
                "value": 70
            },
            {
                "day": 4,
                "hour": 23,
                "value": 55
            },
            {
                "day": 4,
                "hour": 23.5,
                "value": 51
            },
            {
                "day": 4,
                "hour": 24,
                "value": 55
            },
            {
                "day": 5,
                "hour": 1,
                "value": 17
            },
            {
                "day": 5,
                "hour": 1.5,
                "value": 20
            },
            {
                "day": 5,
                "hour": 2,
                "value": 9
            },
            {
                "day": 5,
                "hour": 2.5,
                "value": 4
            },
            {
                "day": 5,
                "hour": 3,
                "value": 0
            },
            {
                "day": 5,
                "hour": 3.5,
                "value": 12
            },
            {
                "day": 5,
                "hour": 4,
                "value": 6
            },
            {
                "day": 5,
                "hour": 4.5,
                "value": 2
            },
            {
                "day": 5,
                "hour": 5,
                "value": 0
            },
            {
                "day": 5,
                "hour": 5.5,
                "value": 0
            },
            {
                "day": 5,
                "hour": 6,
                "value": 0
            },
            {
                "day": 5,
                "hour": 6.5,
                "value": 2
            },
            {
                "day": 5,
                "hour": 7,
                "value": 4
            },
            {
                "day": 5,
                "hour": 7.5,
                "value": 11
            },
            {
                "day": 5,
                "hour": 8,
                "value": 28
            },
            {
                "day": 5,
                "hour": 8.5,
                "value": 49
            },
            {
                "day": 5,
                "hour": 9,
                "value": 51
            },
            {
                "day": 5,
                "hour": 9.5,
                "value": 47
            },
            {
                "day": 5,
                "hour": 10,
                "value": 38
            },
            {
                "day": 5,
                "hour": 10.5,
                "value": 65
            },
            {
                "day": 5,
                "hour": 11,
                "value": 60
            },
            {
                "day": 5,
                "hour": 11.5,
                "value": 50
            },
            {
                "day": 5,
                "hour": 12,
                "value": 65
            },
            {
                "day": 5,
                "hour": 12.5,
                "value": 50
            },
            {
                "day": 5,
                "hour": 13,
                "value": 22
            },
            {
                "day": 5,
                "hour": 13.5,
                "value": 11
            },
            {
                "day": 5,
                "hour": 14,
                "value": 12
            },
            {
                "day": 5,
                "hour": 14.5,
                "value": 9
            },
            {
                "day": 5,
                "hour": 15,
                "value": 0
            },
            {
                "day": 5,
                "hour": 15.5,
                "value": 13
            },
            {
                "day": 5,
                "hour": 16,
                "value": 5
            },
            {
                "day": 5,
                "hour": 16.5,
                "value": 8
            },
            {
                "day": 5,
                "hour": 17,
                "value": 8
            },
            {
                "day": 5,
                "hour": 17.5,
                "value": 0
            },
            {
                "day": 5,
                "hour": 18,
                "value": 0
            },
            {
                "day": 5,
                "hour": 18.5,
                "value": 2
            },
            {
                "day": 5,
                "hour": 19,
                "value": 5
            },
            {
                "day": 5,
                "hour": 19.5,
                "value": 12
            },
            {
                "day": 5,
                "hour": 20,
                "value": 34
            },
            {
                "day": 5,
                "hour": 20.5,
                "value": 43
            },
            {
                "day": 5,
                "hour": 21,
                "value": 54
            },
            {
                "day": 5,
                "hour": 21.5,
                "value": 44
            },
            {
                "day": 5,
                "hour": 22,
                "value": 40
            },
            {
                "day": 5,
                "hour": 22.5,
                "value": 48
            },
            {
                "day": 5,
                "hour": 23,
                "value": 54
            },
            {
                "day": 5,
                "hour": 23.5,
                "value": 59
            },
            {
                "day": 5,
                "hour": 24,
                "value": 60
            },
            {
                "day": 6,
                "hour": 1,
                "value": 51
            },
            {
                "day": 6,
                "hour": 1.5,
                "value": 21
            },
            {
                "day": 6,
                "hour": 2,
                "value": 16
            },
            {
                "day": 6,
                "hour": 2.5,
                "value": 9
            },
            {
                "day": 6,
                "hour": 3,
                "value": 5
            },
            {
                "day": 6,
                "hour": 3.5,
                "value": 4
            },
            {
                "day": 6,
                "hour": 4,
                "value": 7
            },
            {
                "day": 6,
                "hour": 4.5,
                "value": 0
            },
            {
                "day": 6,
                "hour": 5,
                "value": 0
            },
            {
                "day": 6,
                "hour": 5.5,
                "value": 0
            },
            {
                "day": 6,
                "hour": 6,
                "value": 0
            },
            {
                "day": 6,
                "hour": 6.5,
                "value": 0
            },
            {
                "day": 6,
                "hour": 7,
                "value": 2
            },
            {
                "day": 6,
                "hour": 7.5,
                "value": 4
            },
            {
                "day": 6,
                "hour": 8,
                "value": 13
            },
            {
                "day": 6,
                "hour": 8.5,
                "value": 26
            },
            {
                "day": 6,
                "hour": 9,
                "value": 58
            },
            {
                "day": 6,
                "hour": 9.5,
                "value": 61
            },
            {
                "day": 6,
                "hour": 10,
                "value": 59
            },
            {
                "day": 6,
                "hour": 10.5,
                "value": 53
            },
            {
                "day": 6,
                "hour": 11,
                "value": 54
            },
            {
                "day": 6,
                "hour": 11.5,
                "value": 64
            },
            {
                "day": 6,
                "hour": 12,
                "value": 55
            },
            {
                "day": 6,
                "hour": 12.5,
                "value": 52
            },
            {
                "day": 6,
                "hour": 13,
                "value": 53
            },
            {
                "day": 6,
                "hour": 13.5,
                "value": 18
            },
            {
                "day": 6,
                "hour": 14,
                "value": 3
            },
            {
                "day": 6,
                "hour": 14.5,
                "value": 9
            },
            {
                "day": 6,
                "hour": 15,
                "value": 12
            },
            {
                "day": 6,
                "hour": 15.5,
                "value": 2
            },
            {
                "day": 6,
                "hour": 16,
                "value": 8
            },
            {
                "day": 6,
                "hour": 16.5,
                "value": 2
            },
            {
                "day": 6,
                "hour": 17,
                "value": 0
            },
            {
                "day": 6,
                "hour": 17.5,
                "value": 8
            },
            {
                "day": 6,
                "hour": 18,
                "value": 2
            },
            {
                "day": 6,
                "hour": 18.5,
                "value": 0
            },
            {
                "day": 6,
                "hour": 19,
                "value": 2
            },
            {
                "day": 6,
                "hour": 19.5,
                "value": 4
            },
            {
                "day": 6,
                "hour": 20,
                "value": 14
            },
            {
                "day": 6,
                "hour": 20.5,
                "value": 31
            },
            {
                "day": 6,
                "hour": 21,
                "value": 48
            },
            {
                "day": 6,
                "hour": 21.5,
                "value": 46
            },
            {
                "day": 6,
                "hour": 22,
                "value": 50
            },
            {
                "day": 6,
                "hour": 22.5,
                "value": 66
            },
            {
                "day": 6,
                "hour": 23,
                "value": 54
            },
            {
                "day": 6,
                "hour": 23.5,
                "value": 56
            },
            {
                "day": 6,
                "hour": 24,
                "value": 67
            },
            {
                "day": 7,
                "hour": 1,
                "value": 54
            },
            {
                "day": 7,
                "hour": 1.5,
                "value": 23
            },
            {
                "day": 7,
                "hour": 2,
                "value": 14
            },
            {
                "day": 7,
                "hour": 2.5,
                "value": 6
            },
            {
                "day": 7,
                "hour": 3,
                "value": 8
            },
            {
                "day": 7,
                "hour": 3.5,
                "value": 7
            },
            {
                "day": 7,
                "hour": 4,
                "value": 0
            },
            {
                "day": 7,
                "hour": 4.5,
                "value": 8
            },
            {
                "day": 7,
                "hour": 5,
                "value": 2
            },
            {
                "day": 7,
                "hour": 5.5,
                "value": 0
            },
            {
                "day": 7,
                "hour": 6,
                "value": 2
            },
            {
                "day": 7,
                "hour": 6.5,
                "value": 0
            },
            {
                "day": 7,
                "hour": 7,
                "value": 0
            },
            {
                "day": 7,
                "hour": 7.5,
                "value": 0
            },
            {
                "day": 7,
                "hour": 8,
                "value": 4
            },
            {
                "day": 7,
                "hour": 8.5,
                "value": 8
            },
            {
                "day": 7,
                "hour": 9,
                "value": 8
            },
            {
                "day": 7,
                "hour": 9.5,
                "value": 6
            },
            {
                "day": 7,
                "hour": 10,
                "value": 14
            },
            {
                "day": 7,
                "hour": 10.5,
                "value": 12
            },
            {
                "day": 7,
                "hour": 11,
                "value": 9
            },
            {
                "day": 7,
                "hour": 11.5,
                "value": 14
            },
            {
                "day": 7,
                "hour": 12,
                "value": 0
            },
            {
                "day": 7,
                "hour": 12.5,
                "value": 4
            },
            {
                "day": 7,
                "hour": 13,
                "value": 7
            },
            {
                "day": 7,
                "hour": 13.5,
                "value": 6
            },
            {
                "day": 7,
                "hour": 14,
                "value": 0
            },
            {
                "day": 7,
                "hour": 14.5,
                "value": 0
            },
            {
                "day": 7,
                "hour": 15,
                "value": 0
            },
            {
                "day": 7,
                "hour": 15.5,
                "value": 0
            },
            {
                "day": 7,
                "hour": 16,
                "value": 0
            },
            {
                "day": 7,
                "hour": 16.5,
                "value": 0
            },
            {
                "day": 7,
                "hour": 17,
                "value": 7
            },
            {
                "day": 7,
                "hour": 17.5,
                "value": 6
            },
            {
                "day": 7,
                "hour": 18,
                "value": 0
            },
            {
                "day": 7,
                "hour": 18.5,
                "value": 0
            },
            {
                "day": 7,
                "hour": 19,
                "value": 0
            },
            {
                "day": 7,
                "hour": 19.5,
                "value": 0
            },
            {
                "day": 7,
                "hour": 20,
                "value": 0
            },
            {
                "day": 7,
                "hour": 20.5,
                "value": 0
            },
            {
                "day": 7,
                "hour": 21,
                "value": 0
            },
            {
                "day": 7,
                "hour": 21.5,
                "value": 0
            },
            {
                "day": 7,
                "hour": 22,
                "value": 2
            },
            {
                "day": 7,
                "hour": 22.5,
                "value": 2
            },
            {
                "day": 7,
                "hour": 23,
                "value": 5
            },
            {
                "day": 7,
                "hour": 23.5,
                "value": 6
            },
            {
                "day": 7,
                "hour": 24,
                "value": 0
            }
        ];

    function createChart(selector, data) {
        var margin = {top: 0, right: 0, bottom: 0, left: 20},
        width = 200 - margin.left - margin.right,
            height = 675 - margin.top - margin.bottom,
            gridSize = 40,
            buckets = 9,
            colors = ["#ffffd9", "#edf8b1", "#c7e9b4", "#7fcdbb", "#41b6c4", "#1d91c0", "#225ea8", "#253494", "#081d58"];
        var days = ["M", "T", "W", "H", "F", "S", "U"];
        var times = ["1a", "2a", "3a", "4a", "5a", "6a", "7a", "8a", "9a", "10a", "11a", "12a", "1p", "2p", "3p", "4p", "5p", "6p", "7p", "8p", "9p", "10p", "11p", "12p"];

        var shift = (8 - 1) * (gridSize) + 14;

        var colorScale = d3.scale.quantile()
            .domain([0, buckets - 1, d3.max(data, function (d) {
                    return d.value;
                })])
            .range(colors);

        var svg = d3.select(selector).append("svg")
            .attr("height", height + margin.left + margin.right)
            .attr("width", width + margin.top + margin.bottom)
            .append("g")
            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

        var dayLabels = svg.selectAll(".dayLabel")
            .data(days)
            .enter().append("text")
            .text(function (d) {
                return d;
            })
            .attr("y", 0)
            .attr("x", function (d, i) {
                return i * (gridSize * 0.5) - 12;
            })
            .style("text-anchor", "end")
            //.attr("transform", "translate(-6," + gridSize / 1.5 + ")")
            .attr("transform", "translate(" + gridSize / 1.5 + ",-6)")
            .attr("class", function (d, i) {
                return ((i >= 0 && i <= 4) ? "dayLabel mono axis axis-workweek" : "dayLabel mono axis");
            });

        var timeLabels = svg.selectAll(".timeLabel")
            .data(times)
            .enter().append("text")
            .filter(function (d, i) {
                return ((i > 6) && (i < 18));
            })
            .text(function (d) {
                return d;
            })
            .attr("y", function (d, i) {
                return i * gridSize;
            })
            .attr("x", 0)
            .style("text-anchor", "middle")
            //.attr("transform", "translate(" + gridSize / 2 + ", -6)")
            .attr("transform", "translate(-12," + gridSize / 2 + ")")
            .attr("class", function (d, i) {
                return ((i >= 7 && i <= 16) ? "timeLabel mono axis axis-worktime" : "timeLabel mono axis axis-worktime");
            });

        var heatMap = svg.selectAll(".hour")
            .data(data)
            .enter().append("rect")
            .filter(function (d) {
                return ((d.hour > 8) && (d.hour < 19));
            })
            .attr("y", function (d) {
                return (d.hour - 1) * (gridSize) - shift;
            })
            .attr("x", function (d) {
                return (d.day - 1) * (gridSize * 0.5);
            })
            //.attr("ry", 4)
            //.attr("rx", 4)
            .attr("class", "hour bordered")
            .attr("width", gridSize / 2)
            .attr("height", gridSize / 2)
            .style("fill", colors[0]);

        heatMap.transition().duration(1000)
            .style("fill", function (d) {
                return colorScale(d.value);
            });

        heatMap.append("title").text(function (d) {
            return d.value;
        });

        if (selector === '#d3-1') {
            var legend = svg.selectAll(".legend")
                .data([0].concat(colorScale.quantiles()), function (d) {
                    return d;
                })
                .enter().append("g")
                .attr("class", "legend");

            legend.append("rect")
                .attr("x", function (d, i) {
                    return (gridSize * 0.5) * i - 18;
                })
                .attr("y", 450)
                .attr("width", gridSize / 2)
                .attr("height", gridSize / 2)
                .style("fill", function (d, i) {
                    return colors[i];
                });

            legend.append("text")
                .attr("class", "mono")
                .text(function (d) {
                    return Math.round(d);
                })
                .attr("x", function (d, i) {
                    return (gridSize * 0.5) * i - 14;
                })
                .attr("y", 450 + 35)
                .attr("class", function (d, i) {
                    return "timeLabel mono axis axis-worktime";
                });
        }
    }

    function createSelect(tag_prefix, label, multiple) {
        var mult = "";
        var result = '<div class="form-group">';
        if (multiple) {
            mult += 'multiple="multiple"';
        }
        if (label.length > 0) {
            result += '<label for="' + tag_prefix + '-sel">' + label + '</label>';
        }
        result += '<select id="' + tag_prefix + '-sel" ' + mult + '  class="vis-select"></select></div>';
        return result;
    }

    function render() {

        var html = '<div style="margin: 20px 20px 20px 20px;"><div id="d3-1" class="col-md-2"></div>\n\
        <div id="d3-2" class="col-md-2"></div>\n\
        <div id="d3-3" class="col-md-2"></div>\n\
        <div id="d3-4" class="col-md-2"></div>\n\
        <div id="d3-5" class="col-md-2"></div></div>';
        $("#content").html(html);

        $('#d3-1').append(createSelect('sched1', 'Schedule 1', false));
        $('#d3-2').append(createSelect('sched2', 'Schedule 2', false));
        $('#d3-3').append(createSelect('sched3', 'Schedule 3', false));
        $('#d3-4').append(createSelect('sched4', 'Schedule 4', false));
        $('#d3-5').append(createSelect('sched5', 'Schedule 5', false));

        var result = "";
        for (var i = 2000; i <= 2014; i++) {
            result += '<option value="' + i + '-FALL">' + i + '-Fall</option><option value="' + i + '-SPRING">' + i + '-Spring</option><option value="' + i + '-SUMMER">' + i + '-Summer</option>';
        }
        $('#sched1-sel').append(result);
        $('#sched2-sel').append(result);
        $('#sched3-sel').append(result);
        $('#sched4-sel').append(result);
        $('#sched5-sel').append(result);

        $('select').multiselect({
            maxHeight: 300,
            buttonWidth: '185px'
        });

        chart1 = createChart('#d3-1', data);
        chart2 = createChart('#d3-2', data);
        chart3 = createChart('#d3-3', data);
        chart4 = createChart('#d3-4', data);
        chart5 = createChart('#d3-5', data);

        for (var i = 1; i <= 7; i++) {
            for (var j = 0.0; j <= 24; j+= 0.5) {
                console.log(i + "," + j + "\n");
            }
        }
    }

    return {
        render: render
    }

}());
