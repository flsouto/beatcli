from pyjoystick.sdl2 import Key, Joystick, run_event_loop
from subprocess import Popen
import os, signal
import time
import sys
from argparse import ArgumentParser

ap = ArgumentParser()
ap.add_argument('--mixers','-m')
args,_ = ap.parse_known_args()

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
Key.L2 = "Button 6"
Key.START = "Button 9"
Key.SELECT = "Button 8"
Key.LEFT = "Hat 0 [Left]"
Key.RIGHT = "Hat 0 [Right]"
Key.DOWN = "Hat 0 [Down]"
Key.UP = "Hat 0 [Up]"

proc = None
curr_mode = 1
tag_mode = False
selected_tag = ''

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


def next(mode = None):
    global curr_mode, tag_mode, selected_tag
    tag_mode = False
    selected_tag = ''
    match (mode or curr_mode):
        case 1:
            if args.mixers:
                from random import choice
                run("php", "mix.php", choice(args.mixers.split(',')))
            else:
                run("php", "mix.php")
            curr_mode = 1
        case 2:
            run("php", "mix.php", "samplicat")
#            run("php", "mix.php", "_bit2", "../looploader/queue/*.wav")
            curr_mode = 2
        case 3:
            run("php", "mix.php", "meshterns")
            curr_mode = 3

def get_env(prefix):
    global selected_tag
    env = os.environ.copy()

    if selected_tag:
        if prefix == 'amb' and selected_tag == 'amb':
            prefix = 'hor'
        else:
            prefix += selected_tag

    if prefix:
        env['PREFIX'] = prefix

    return env

def pub(prefix=''):
    env = get_env(prefix)
    run2("php", "save.php",env=env).wait()
    run2("php", "pub.php",env=env).wait()

def save(prefix=''):
    env = get_env(prefix)
    run2("php", "save.php",env=env).wait()

def mod(*fx):
    run2("sox","stage.wav","tmp.wav",*fx).wait()
    run2("mv","tmp.wav","stage.wav").wait()

def play():
    run("play", "stage.wav")

key2tag = {
	Key.X : 'gli',
	Key.TRIANGLE: 'tri',
	Key.SQUARE : 'syn',
	Key.CIRCLE : 'amb'
}

def key_received(input):

    global proc, curr_mode, tag_mode, selected_tag

    Key.enabled = not Key.enabled

    if not Key.enabled: return

    if tag_mode and input in key2tag:
        selected_tag = key2tag[input]
        print(f"tag selected: {selected_tag}")
        tag_mode = False
        return

    match input:
        case Key.R1: next(1)
        case Key.R2: next(2)
        case Key.L1: next(3)
        case Key.START:
            if proc and not proc.poll():
                abort()
            else:
                play()
        case Key.SELECT:
            print("tag mode activated")
            tag_mode = True
        case Key.X:
            save()
            next()
        case Key.SQUARE:
            pub()
            next()
        case Key.TRIANGLE:
            if curr_mode == 1:
                pub('exc')
            else:
                save('excs')
            next()
        case Key.CIRCLE:
            pub('amb')
            next()
        case Key.RIGHT:
            mod("repeat", "1")
            play()
        case Key.DOWN:
            mod("gain", "-5")
            play()
        case Key.UP:
            mod("gain", "5")
            play()
        case default:
            print("Unmapped key: ",input)

run_event_loop(print_add, print_remove, key_received)
