const int proxSensor= A0;
const int BUZZER = D8;
const int ssPin = D3;
const int resetPin = D4; 
const int Relay = D0;
int val = 0;
int count = 0;
int function = 0;
int i = 0;
String serverName = "http://131.107.2.19/handgel/arduino.php";
String rfid, sthealth;
int distance = 0;
#include <Adafruit_MLX90614.h>
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClient.h>
#include <DNSServer.h>
#include <ESP8266WebServer.h>
#include <WiFiManager.h>
#include <Wire.h>
#include <SPI.h>
#include "MFRC522.h"
#include <Wire.h>
MFRC522 mfrc522(ssPin, resetPin);
Adafruit_MLX90614 mlx = Adafruit_MLX90614();

void setup() {
Serial.begin(115200);
SPI.begin();     
mfrc522.PCD_Init();  
mfrc522.PCD_DumpVersionToSerial();
Serial.println(F("Scan PICC to see UID, SAK, type, and data blocks..."));
mlx.begin();
pinMode(Relay, OUTPUT);
digitalWrite(Relay, HIGH);
pinMode(BUZZER, OUTPUT);
  WiFiManager wifiManager;
  wifiManager.autoConnect("AutoConnectAP");
  Serial.println("Connecting");
  while(WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
    
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());
  Serial.println("Read UID CARD & Show Your Hand");
}
 
void loop() {
  
  rfid="";
  if(WiFi.status()== WL_CONNECTED){
     if (! mfrc522.PICC_IsNewCardPresent()) {
      return;
      }
      //เลือกบัตร 1 ใบ
  if (! mfrc522.PICC_ReadCardSerial()) {
      return;
     }else{
      readcard(); //อ่านบัตรนักเรียน
      }
      
      distance = analogRead(proxSensor);
      
      if (distance <= 50) {
      Serial.println("Hand Detect");
      readtemp(); //อ่านอุณหภูมิ
      digitalWrite(Relay, LOW); // ส่งให้ไฟติด
      Serial.println("Relay On");
      delay(1000);
      Serial.println("Relay off");
      sendto(); //ส่งค่าเข้าเว็บ
      }if (distance >= 50){
        Serial.println("No Hand Detect");
        Serial.println("please read card again");
        Serial.println("Relay off");
        digitalWrite(Relay, HIGH); // ส่งให้ไฟดับ
        delay(1000);
        }
    }
delay(1000);
}

void readtemp(){
    val = mlx.readObjectTempC();

    if(val >= 37.5){
    sthealth = "COVID!";
        for(i = 1; i <= 10; i++)
        {
            analogWrite(BUZZER, 200);
            delay(100);
            analogWrite(BUZZER, 25);
            delay(100);
        }
    Serial.println("Light Red On");
    }else{
    sthealth = "OKAY!";
    Serial.println("Light Green On");
    }
}

void readcard(){
     Serial.println(" ");
     Serial.print("UID tag : ");
  
    for (byte i = 0; i < mfrc522.uid.size; i++) 
    {
     Serial.print(mfrc522.uid.uidByte[i] < 0x10 ? "0" : "");
     Serial.print(mfrc522.uid.uidByte[i], DEC);
     rfid.concat(String(mfrc522.uid.uidByte[i] < 0x10 ? "0" : ""));
     rfid.concat(String(mfrc522.uid.uidByte[i], DEC));
    }
    rfid.toUpperCase();
    //stdcode = String(rfid);
    Serial.print(rfid);
    tone(BUZZER, 1000);
    delay(100);
    noTone(BUZZER);
    Serial.println(count);
    delay(2000);   
  }

void sendto(){
      HTTPClient http;
      String serverPath = serverName + "?rfid=" + rfid + "&temp=" + String(val) + "&heal=" + sthealth;

     // Your Domain name with URL path or IP address with path
      http.begin(serverPath.c_str());
      
      // Send HTTP GET request
      int httpResponseCode = http.GET();
      
      if(httpResponseCode != 200){
        Serial.print("Error code: ");
        Serial.println(httpResponseCode);
        return;
      }
      else {
        Serial.print("HTTP Response code: ");
        Serial.println(httpResponseCode);
        String payload = http.getString();
        Serial.println();
        Serial.println(payload);
        Serial.println(serverPath);
//      }
      // Free resources
        http.end();
     }
}
