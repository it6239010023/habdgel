const int proxSensor= A0;
const int BUZZER = D8;
const int ssPin = D3;
const int resetPin = D4; 
const int relay = D0;
int times = 200;
int val = 0;
int count = 0;
int function = 0;
int i = 0;
String serverName = "http://131.107.1.150/handgel/arduino.php";
String rfid, sthealth, hand, datalcd, payload;
int distance = 0;

#include <Adafruit_MLX90614.h>
#include <LiquidCrystal_I2C.h>
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

LiquidCrystal_I2C lcd(0x27, 16, 2);
MFRC522 mfrc522(ssPin, resetPin);
Adafruit_MLX90614 mlx = Adafruit_MLX90614();

void setup() {
Serial.begin(115200);
SPI.begin();    
mfrc522.PCD_Init();  
mfrc522.PCD_DumpVersionToSerial();
Serial.println(F("Scan PICC to see UID, SAK, type, and data blocks..."));
mlx.begin();
lcd.begin(16,2);
lcd.backlight();
lcd.setCursor(0, 0);
lcd.print("Waiting. Please!");
pinMode(relay, OUTPUT);
digitalWrite(relay, HIGH);
pinMode(BUZZER, OUTPUT);
  WiFiManager wifiManager;
  wifiManager.autoConnect("AutoConnectAP");
  Serial.println("Connecting");
  while(WiFi.status() != WL_CONNECTED) {
    delay(times);
    Serial.print(".");
    lcd.setCursor(1, 0);
    lcd.print("Loading...");
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());
  Serial.println("Read UID CARD & Show Your Hand");
  lcd.clear();
  lcd.setCursor(0,0);
  lcd.print("Smart CheckPoint");
  lcd.setCursor(1,1);
  lcd.print("Check " + String(count) + " Person" );
}
 
void loop() { 
  if(WiFi.status()== WL_CONNECTED){
      rfid="";
     if (! mfrc522.PICC_IsNewCardPresent()) {
      return;
      }
      //เลือกบัตร 1 ใบ
  if (! mfrc522.PICC_ReadCardSerial()) {
      return;
     }
     readcard();
     handdistance(); //เช็คระยะมือ
     else{
     Serial.println(" ");
     Serial.print("UID tag : ");
      }
      delay(times);
  }


void readtemp(){
    val = mlx.readObjectTempC();
        if(val >= 37.5){
    sthealth = "มีความเสี่ยง";
    datalcd = "Covid!";
    Serial.println("Light Red On");
        for(i = 1; i <= 10; i++)
        {
            analogWrite(BUZZER, 300);
            delay(100);
            analogWrite(BUZZER, 25);
            delay(100);
        }
    }else{
    sthealth = "ไม่มีความเสี่ยง";
    datalcd = "Okay!";
    Serial.println("Light Green On");
    }
    tone(BUZZER, 1000);
    delay(100);
    noTone(BUZZER);
}

void readcard(){
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
    lcd.clear();
    lcd.setCursor(3,0);
    lcd.print("ID : ");
    lcd.print(rfid);
    lcd.setCursor(0, 1);
    lcd.print("Save!!!");
    tone(BUZZER, 1000);
    delay(times);
    noTone(BUZZER);
    Serial.println(count);
    delay(times);   
  }
}
void handdistance(){
      distance = analogRead(proxSensor);
      if (distance <= 50) {
        Serial.println("Hand Detect");
        readtemp(); //อ่านอุณหภูมิ
        digitalWrite(relay, LOW); // ส่งให้ไฟติด
        Serial.println("Relay On");
        delay(300);
        digitalWrite(relay, HIGH);
        Serial.println("Relay off");
        delay(times);
        hand = "จ่ายเจลแล้ว";
        count++;
        Serial.println(count);
        lcdshow(); //โชว์จอ Lcd    
        lcd.clear();
      }
  }
void sendto(){
      HTTPClient http;
      String serverPath = serverName + "?rfid=" + rfid + "&temp=" + String(val) + "&heal=" + sthealth + "&hand=" + hand;

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
        payload = http.getString();
        Serial.println();
        Serial.println(payload);
        Serial.println(serverPath);
        http.end();
     }
}

void lcdshow(){
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("TP: ");
    lcd.setCursor(4, 0);
    lcd.print(val);
    lcd.setCursor(6, 0);
    lcd.print((char) 223);
    lcd.print("C");
    lcd.setCursor(9, 0);
    lcd.print(datalcd);
      }
    delay(times);
