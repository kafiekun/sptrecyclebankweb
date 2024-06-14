import RPi.GPIO as GPIO
import time

# Set GPIO mode
GPIO.setmode(GPIO.BCM)

# Define GPIO pins
GPIO_TRIGGER = 23
GPIO_ECHO = 24

# Set trigger to output and echo to input
GPIO.setup(GPIO_TRIGGER, GPIO.OUT)
GPIO.setup(GPIO_ECHO, GPIO.IN)

def measure_distance():
    # Set trigger to HIGH
    GPIO.output(GPIO_TRIGGER, True)
    # Set trigger to LOW after 10 microseconds
    time.sleep(0.00001)
    GPIO.output(GPIO_TRIGGER, False)
    
    start_time = time.time()
    stop_time = time.time()
    
    # Save start_time
    while GPIO.input(GPIO_ECHO) == 0:
        start_time = time.time()
    
    # Save stop_time
    while GPIO.input(GPIO_ECHO) == 1:
        stop_time = time.time()
    
    # Calculate time difference
    time_elapsed = stop_time - start_time
    # Calculate distance (34300 cm/s is the speed of sound in air)
    distance = (time_elapsed * 34300) / 2
    
    return distance

try:
    while True:
        dist = measure_distance()
        if dist < 10:
            print("Object detected")
        else:
            print("No object detected")
        time.sleep(1)  # Wait 1 second before next measurement