#include <ESP8266WiFi.h>
#define SERVER_PORT 80          //กำหนดใช้ Port 80

const char* ssid = ".@BNCC-EAP";
const char* password = "";
 
const char* server_ip = "web.bncc.ac.th";   //กำหนดชื่อ Server ที่ต้องการเชื่อมต่อ
/* กำหนดค่าคำสั่ง HTTP GET */

String str_get1  = "GET /site/6239010023/handgel/index.php?st=";  
String stname="Bangna1";
String str_get2 = "&c="; 
int count=0;
String str_get3  = " HTTP/1.1\r\n";
String str_host = "Host: web.bncc.ac.th\r\n\r\n";

int proxSensor= D8;

WiFiServer server(SERVER_PORT);     //เปิดใช้งาน TCP Port 80
WiFiClient client;              //ประกาศใช้  client 
 
unsigned long previousMillis = 0;       //กำหนดตัวแปรเก็บค่า เวลาสุดท้ายที่ทำงาน    
const long interval = 10000;            //กำหนดค่าตัวแปร ให้ทำงานทุกๆ 10 วินาที
 
void setup() 
{
  pinMode(proxSensor, INPUT);
    Serial.begin(115200);
    WiFi.begin(ssid, password);         //เชื่อมต่อกับ AP
   
     while (WiFi.status() != WL_CONNECTED)  //ตรวจเช็ค และ รอจนเชื่อมต่อ AP สำเร็จ
    {
            delay(500);
            Serial.print(".");
    }
   
    Serial.println("");
    Serial.println("WiFi connected");  
    Serial.println("IP address: ");         
    Serial.println(WiFi.localIP());         //แสดง IP Address ที่ได้   
}
 
void loop() 
{
    if(digitalRead(proxSensor)== LOW){
      Serial.print("Hand Detect");
      count++;
      while(digitalRead(proxSensor)== LOW);
      }
      
    while(client.available())               //ตรวจเช็คว่ามีการส่งค่ากลับมาจาก Server หรือไม่        
    {
          String line = client.readStringUntil('\n');       //อ่านค่าที่ Server ตอบหลับมาทีละบรรทัด
          Serial.println(line);             //แสดงค่าที่ได้ทาง Serial Port
    }
  unsigned long currentMillis = millis();           //อ่านค่าเวลาที่ ESP เริ่มทำงานจนถึงเวลาปัจจุบัน
  if(currentMillis - previousMillis >= interval&&count!=0)     /*ถ้าหากเวลาปัจจุบันลบกับเวลาก่อหน้านี้ มีค่า
                            มากกว่าค่า interval ให้คำสั่งภายใน if ทำงาน*/ 
  {
        previousMillis = currentMillis;         /*ให้เวลาปัจจุบัน เท่ากับ เวลาก่อนหน้าเพื่อใช้
                            คำนวณเงื่อนไขในรอบถัดไป*/
        Client_Request();               //เรีกใช้งานฟังก์ชั่น Client_Request 
        count=0;
  }   
  
}
 
void Client_Request()
{
    Serial.println("Connect TCP Server");
    int cnt=0;
    while (!client.connect(server_ip,SERVER_PORT))  //เชื่อมต่อกับ Server และรอจนกว่าเชื่อมต่อสำเร็จ
    {
          Serial.print(".");
          delay(100);
          cnt++;
          if(cnt>50)                 //ถ้าหากใช้เวลาเชื่อมต่อเกิน 5 วินาที ให้ออกจากฟังก์ชั่น
          return;
    } 
    Serial.println("Success");
    delay(500);
   client.print(str_get1+str_get2+String(count)+str_get3+str_host);       //ส่งคำสั่ง HTTP GET ไปยัง Server
   Serial.print(str_get1+str_get2+String(count)+str_get3+str_host);
   delay(100);
}
