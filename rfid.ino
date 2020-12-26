#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClient.h>
#include <Wire.h>
#include <SPI.h>
#include "MFRC522.h"
#include <Wire.h>
#include <Adafruit_MLX90614.h>

//const char* ssid = ".@IT-LAB";
//const char* password = "212224236";

const char* ssid = "Hi Vivo";
const char* password = "KENG5123";

//Your Domain name with URL path or IP address with path
String serverName = "http://web.bncc.ac.th/site/6239010023/handgel/health.php";
String rfid= "";
int val = 0;
int i = 0; 

// the following variables are unsigned longs because the time, measured in
// milliseconds, will quickly become a bigger number than can be stored in an int.
unsigned long lastTime = 0;
// Timer set to 10 minutes (600000)0
//unsigned long timerDelay = 600000;
// Set timer to 5 seconds (5000)
String stdcode, sthealth;
const int ssPin = D3; // Slave select pin
const int resetPin = D4; // Reset pin
const int BUZZER =  D0; 
MFRC522 mfrc522(ssPin, resetPin);   // Create MFRC522 instance.
Adafruit_MLX90614 mlx = Adafruit_MLX90614();


void setup()
{
  Serial.begin(115200);
  SPI.begin();      // Init SPI bus
  mfrc522.PCD_Init();   // Init MFRC522
  mfrc522.PCD_DumpVersionToSerial();  // Show details of PCD - MFRC522 Card Reader details
  Serial.println(F("Scan PICC to see UID, SAK, type, and data blocks..."));
  mlx.begin(); 
  pinMode(BUZZER, OUTPUT);
  //randomSeed(analogRead(0));
  
  WiFi.begin(ssid, password);
  Serial.println("Connecting");
  while(WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
    
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());
  Serial.println("Please Check your Tempreture!!!");
}


void loop()
{
    rfid="";
    //Check WiFi connection status
    if(WiFi.status()== WL_CONNECTED){
      
    if ( ! mfrc522.PICC_IsNewCardPresent()) {
    return;
    }

    //เลือกบัตร 1 ใบ
    if ( ! mfrc522.PICC_ReadCardSerial()) {
    return;
    }
     
    //วัดอุณหภูมิ
    val = mlx.readObjectTempC();
    Serial.print("Temp = ");
    Serial.print(val);
    Serial.print(" C*");
  
    Serial.print(" UID tag : ");
  
    for (byte i = 0; i < mfrc522.uid.size; i++) 
    {
     Serial.print(mfrc522.uid.uidByte[i] < 0x10 ? "0" : "");
     Serial.print(mfrc522.uid.uidByte[i], DEC);
     rfid.concat(String(mfrc522.uid.uidByte[i] < 0x10 ? "0" : ""));
     rfid.concat(String(mfrc522.uid.uidByte[i], DEC));
    }
    rfid.toUpperCase();
    stdcode = String(rfid);
    Serial.print(rfid);
  
    if(val >= 37.5){
    sthealth = "COVID!";
    Serial.print(" Status : ");
    Serial.println(sthealth);
        for(i = 1; i <= 10; i++)
        {
            analogWrite(BUZZER, 200);
            delay(100);
            analogWrite(BUZZER, 25);
            delay(100);
        }
    }else{
    sthealth = "OKAY!";
    Serial.print(" Status : ");
    Serial.println(sthealth);
    }

    tone(BUZZER, 1000);
    delay(100);
    noTone(BUZZER);
  
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
      else {
      Serial.println("WiFi Disconnected");
      }
      delay(1000);
}
