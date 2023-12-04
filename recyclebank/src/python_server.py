from flask import Flask, request, jsonify
import json
import RPi.GPIO as GPIO
import time

sensorGeneral = 4
sensorPlastic = 18
sensorBottle = 17
sensorCan = 19
 
GPIO.setwarnings(False)
GPIO.setmode(GPIO.BCM)
GPIO.setup(sensorGeneral, GPIO.IN)
GPIO.setup(sensorPlastic, GPIO.IN)
GPIO.setup(sensorBottle, GPIO.IN)
GPIO.setup(sensorCan, GPIO.IN)
 
app = Flask(__name__)

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
            if GPIO.input(sensorGeneral) == 1:
                return "true"
        
        if firstSignalPlastic:
            if GPIO.input(sensorPlastic) == 1:
                return "true"
 
        if firstSignalBottle:
            if GPIO.input(sensorBottle) == 1:
                return "true"        
 
        if firstSignalCan:
            if GPIO.input(sensorCan) == 1:
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
