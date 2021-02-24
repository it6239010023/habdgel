const int proxSensor= A0;
const int BUZZER = D8;
const int ssPin = D3;
const int resetPin = D4; 
const int relay = D0;
int cnt = 10;
int val = 0;
int count = 0;
int function = 0;
int i = 0;
//String serverName = "http://131.107.2.19/handgel/arduino.php";
String serverName = "http://192.168.0.151/handgel/arduino.php";
String rfid, sthealth, hand, datalcd;
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
lcd.begin();
lcd.backlight(); 
pinMode(relay, OUTPUT);
digitalWrite(relay, HIGH);
pinMode(BUZZER, OUTPUT);
  WiFiManager wifiManager;
  wifiManager.autoConnect("AutoConnectAP");
  Serial.println("Connecting");
  while(WiFi.status() != WL_CONNECTED) {
    delay(500);
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
      handdistance(); //เช็คระยะมือ
  }
delay(1000);
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
    lcd.clear();
    lcd.setCursor(3,0);
    lcd.print("ID OKAY...");
    lcd.setCursor(0, 1);
    lcd.print("Show Hand Please!");
    tone(BUZZER, 1000);
    delay(100);
    noTone(BUZZER);
    Serial.println(count);
    delay(2000);   
  }

void handdistance(){
      distance = analogRead(proxSensor);
      if (distance <= 50) {
      Serial.println("Hand Detect");
      readtemp(); //อ่านอุณหภูมิ
      digitalWrite(relay, LOW); // ส่งให้ไฟติด
      Serial.println("Relay On");
      delay(1000);
      digitalWrite(relay, HIGH);
      hand = "จ่ายเจลแล้ว";
      Serial.println("Relay off");
      count++;
      Serial.println(count);
      lcdshow(); //โชว์จอ Lcd    
      sendto(); //ส่งค่าเข้าเว็บ
      lcd.clear();
      lcd.setCursor(0,0);
      lcd.print("Smart CheckPoint");
      lcd.setCursor(1,1);
      lcd.print("Check " + String(count) + " Person" );
      }
      if (distance >= 50){        
        Serial.println("No Hand Detect");
        sthealth = "ไม่พบมือ";
        datalcd = "Error!";
        val = 00.00;
        hand = "ไม่ได้รับเจล";
        for(i = 1; i <= 10; i++)
        {
            analogWrite(BUZZER, 300);
            delay(100);
            analogWrite(BUZZER, 25);
            delay(100);
        }
            tone(BUZZER, 1000);
            delay(100);
            noTone(BUZZER);
        lcdshow(); //โชว์จอ Lcd
        sendto(); //ส่งค่าเข้าเว็บ
        lcd.clear();
        lcd.setCursor(0,0);
        lcd.print("Smart CheckPoint");
        lcd.setCursor(1,1);
        lcd.print("Check " + String(count) + " Person" );
     }
    delay(1000);
  }

void lcdshow(){
    lcd.clear();
    //    แถวแรก
    lcd.setCursor(0, 0);
    lcd.print("ID: ");
    lcd.setCursor(4, 0);
    lcd.print(rfid);
    //    แถวสอง
    lcd.setCursor(0, 1);
    lcd.print("TP: ");
    lcd.setCursor(4, 1);
    lcd.print(val);
    lcd.setCursor(6, 1);
    lcd.print((char) 223);
    lcd.print("C");
    lcd.setCursor(9, 1);
    lcd.print(datalcd);   
    delay(2000);
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
        String payload = http.getString();
        Serial.println();
        Serial.println(payload);
        Serial.println(serverPath);
//      }
      // Free resources
        http.end();
     }
}
