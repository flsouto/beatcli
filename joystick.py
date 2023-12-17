from pyjoystick.sdl2 import Key, Joystick, run_event_loop
from subprocess import Popen
import os, signal
import time

def print_add(joy):
    print('Added', joy)

def print_remove(joy):
    print('Removed', joy)

Key.enabled = False
Key.X = "Button 2"
Key.SQUARE = "Button 3"
Key.CIRCLE = "Button 1"
Key.TRIANGLE = "Button 0"
Key.R1 = "Button 5"
Key.R2 = "Button 7"
Key.L1 = "Button 4"
Key.START = "Button 9"
Key.SELECT = "Button 8"

proc = None

def abort():
    global proc
    if proc:
        try:
            os.killpg(os.getpgid(proc.pid), signal.SIGTERM)
        except: pass
    proc = None

def run(*args,**kwargs):
    global proc
    abort()
    proc = Popen(args,**kwargs)
    return proc

def run2(*args, **kwargs):
    return Popen(args, **kwargs)

curr_mode = 1
def next(mode = None):
    global curr_mode
    match (mode or curr_mode):
        case 1:
            run("php", "mix.php")
            curr_mode = 1
        case 2:
            run("php", "mix.php", "_bit", "chop/*.wav")
            curr_mode = 2

def pub(prefix=None):
    env = os.environ.copy()
    if prefix:
        env['PREFIX'] = prefix
    run2("php", "save.php",env=env).wait()
    run2("php", "pub.php",env=env).wait()

def key_received(input):

    global proc

    Key.enabled = not Key.enabled

    if not Key.enabled: return

    match input:
        case Key.R1: next(1)
        case Key.R2: next(2)
        case Key.START:
            if proc and not proc.poll():
                abort()
            else:
                run("play", "stage.wav")
        case Key.X:
            run2("php", "save.php").wait()
            next()
        case Key.SQUARE:
            pub()
            next()
        case Key.TRIANGLE:
            pub('exc')
            next()
        case Key.CIRCLE:
            pub('amb')
            next()
        case default:
            print("Unmapped key: ",input)

run_event_loop(print_add, print_remove, key_received)
