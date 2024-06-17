import RPi.GPIO as GPIO
import time

TRIG_PIN = 23
ECHO_PIN = 24

GPIO.setwarnings(False)
GPIO.setmode(GPIO.BCM)
GPIO.setup(TRIG_PIN, GPIO.OUT)
GPIO.setup(ECHO_PIN, GPIO.IN)

def get_distance():
    GPIO.output(TRIG_PIN, True)
    time.sleep(0.00001)
    GPIO.output(TRIG_PIN, False)

    start_time = time.time()
    stop_time = time.time()

    while GPIO.input(ECHO_PIN) == 0:
        start_time = time.time()

    while GPIO.input(ECHO_PIN) == 1:
        stop_time = time.time()

    time_elapsed = stop_time - start_time
    distance = (time_elapsed * 34300) / 2

    return distance

try:
    print("Starting distance measurement loop...")
    while True:
        distance = get_distance()
        print(f"Measured distance: {distance:.1f} cm")
        if distance < 10:
            print(f"Object detected at {distance:.1f} cm")
        else:
            print("No object detected")
        time.sleep(0.5)

except KeyboardInterrupt:
    print("Exiting..")
    GPIO.cleanup()

except Exception as e:
    print(f"An error occurred: {e}")
    GPIO.cleanup()
