/**
 * This file is part of the Simple demo web-project with REST Full API for Mobile.
 *
 * This project is no longer maintained.
 * The project is written in Zend Framework 2 Release.
 *
 * @link https://github.com/scorpion3dd
 * @copyright Copyright (c) 2016-2021 Denis Puzik <scorpion3dd@gmail.com>
 */

/**
 *
 * @constructor
 */
function Zf2ConsoleWrite() {
    Zf2Console.consoleLog('Zf2Console Log');
    Zf2Console.consoleWarn('Zf2Console Warn');
    Zf2Console.consoleError('Zf2Console Error');
    Zf2Console.consoleInfo('Zf2Console Info');
    Zf2Console.consoleCrazyLog('Zf2Console CrazyLog');
    Zf2Console.consoleGradientLog('Zf2Console consoleGradientLog');
    Zf2Console.consoleColorsLog('Zf2Console consoleColorLog');
    Zf2Console.consoleColorLog('Zf2Console consoleColorLog');
    Zf2Console.consoleTable('Zf2Console Table', ['aaa', 'bbb', 3, 4, 'ccc', 5]);
    Zf2Console.consoleTrace('Zf2Console Trace');
    Zf2Console.consoleAssert('Zf2Console Assert false');
    Zf2Console.consoleAssert('Zf2Console Assert true', true);
    Zf2Console.consoleGroup('Zf2Console Group',['begin', '1', '2', '3', 'end']);
}