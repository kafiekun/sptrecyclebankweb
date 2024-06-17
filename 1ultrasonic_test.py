import RPi.GPIO as GPIO
import time

# GPIO pins
TRIG_PIN = 23  # Trigger pin
ECHO_PIN = 24  # Echo pin
Buzzer = 26

GPIO.setwarnings(False)
GPIO.setmode(GPIO.BCM)
GPIO.setup(TRIG_PIN, GPIO.OUT)
GPIO.setup(ECHO_PIN, GPIO.IN)
GPIO.setup(Buzzer, GPIO.OUT)

def get_distance():
    # Send a pulse to trigger the ultrasonic sensor
    GPIO.output(TRIG_PIN, True)
    time.sleep(0.00001)  # 10 microseconds
    GPIO.output(TRIG_PIN, False)

    # Measure the time for the echo to return
    start_time = time.time()
    stop_time = time.time()

    while GPIO.input(ECHO_PIN) == 0:
        start_time = time.time()

    while GPIO.input(ECHO_PIN) == 1:
        stop_time = time.time()

    # Calculate the distance based on the time measured
    time_elapsed = stop_time - start_time
    distance = (time_elapsed * 34300) / 2  # Speed of sound: 34300 cm/s

    return distance

try:
    while True:
        distance = get_distance()
        if distance < 10:  # Threshold distance set to 10 cm
            GPIO.output(Buzzer, GPIO.HIGH)
            print("Object detected at {:.1f} cm".format(distance))
        else:
            print("No object detected")
            GPIO.output(Buzzer, GPIO.LOW)
        time.sleep(0.5)

