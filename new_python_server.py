from flask import Flask, request, jsonify
import json
import RPi.GPIO as GPIO
import time

# Define GPIO pins for ultrasonic sensors
sensorGeneralTrig = 5
sensorGeneralEcho = 6
sensorPlasticTrig = 13
sensorPlasticEcho = 19
sensorBottleTrig = 20
sensorBottleEcho = 21
sensorCanTrig = 23
sensorCanEcho = 24

GPIO.setwarnings(False)
GPIO.setmode(GPIO.BCM)

# Set up GPIO pins for ultrasonic sensors
GPIO.setup(sensorGeneralTrig, GPIO.OUT)
GPIO.setup(sensorGeneralEcho, GPIO.IN)
GPIO.setup(sensorPlasticTrig, GPIO.OUT)
GPIO.setup(sensorPlasticEcho, GPIO.IN)
GPIO.setup(sensorBottleTrig, GPIO.OUT)
GPIO.setup(sensorBottleEcho, GPIO.IN)
GPIO.setup(sensorCanTrig, GPIO.OUT)
GPIO.setup(sensorCanEcho, GPIO.IN)

app = Flask(__name__)

def get_distance(trig, echo):
    # Set Trigger to HIGH
    GPIO.output(trig, True)
 
    # Set Trigger after 0.01ms to LOW
    time.sleep(0.00001)
    GPIO.output(trig, False)
 
    StartTime = time.time()
    StopTime = time.time()
 
    # Save StartTime
    while GPIO.input(echo) == 0:
        StartTime = time.time()
 
    # Save time of arrival
    while GPIO.input(echo) == 1:
        StopTime = time.time()
 
    # Time difference between start and arrival
    TimeElapsed = StopTime - StartTime
    # Multiply with the sonic speed (34300 cm/s)
    # and divide by 2, because there and back
    distance = (TimeElapsed * 34300) / 2
 
    return distance

@app.route('/receive_data', methods=['POST'])
def receive_data():
    while True:
        data = request.json  # Parse the JSON data from the request
        
        # Process the data here (e.g., print it)
        firstSignalGeneral = data["firstSignalGeneral"]
        firstSignalPlastic = data["firstSignalPlastic"]
        firstSignalBottle = data["firstSignalBottle"]
        firstSignalCan = data["firstSignalCan"]
        
        if firstSignalGeneral:
            if get_distance(sensorGeneralTrig, sensorGeneralEcho) < 10:  # Adjust distance threshold as needed
                return "true"
        
        if firstSignalPlastic:
            if get_distance(sensorPlasticTrig, sensorPlasticEcho) < 10:  # Adjust distance threshold as needed
                return "true"
 
        if firstSignalBottle:
            if get_distance(sensorBottleTrig, sensorBottleEcho) < 10:  # Adjust distance threshold as needed
                return "true"        
 
        if firstSignalCan:
            if get_distance(sensorCanTrig, sensorCanEcho) < 10:  # Adjust distance threshold as needed
                return "true"
     
received_data = None
 
@app.route('/update_endpoint', methods=['POST'])
def update_endpoint():
    global received_data
    try:
        data = request.form.get('data')
        if data != "4":
            received_data = json.loads(data)
            print("Received Data:", received_data)
            return "Data received successfully", 200
        else:
            received_data = json.loads(data)
            return "Empty data", 200
    except Exception as e:
        print("Error processing data:", str(e))
        return "Error processing data", 500
 
@app.route('/check_update_endpoint', methods=['GET'])
def check_update_endpoint():
    global received_data
    try:
        if received_data:
            return jsonify(received_data), 200
        else:
            return "No data Available", 500
    except Exception as e:
        print("Error retrieving data:", str(e))
        return "Error retrieving data", 500
 
if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)
