package com.example.a18965.hitctf;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.view.View$OnClickListener;
import android.view.View;
import android.widget.Toast;

public class MainActivity extends AppCompatActivity {
    public MainActivity() {
        super();
    }

    protected void onCreate(Bundle arg5) {
        super.onCreate(arg5);
        this.setContentView(2130968602);
        this.findViewById(2131427414).setOnClickListener(new View$OnClickListener() {
            public void onClick(View arg11) {
                String v3 = MainActivity.this.findViewById(2131427413).getText().toString();
                int v0 = 0;
                if(v3.length() == 25) {
                    int v1 = 0;
                    while(v1 < "kmqgwg]Tm3=NE_/4ouKJW@WE^".length()) {
                        if("#$%$#!&#^_^~(:p@_*#######".charAt(v1) != ("kmqgwg]Tm3=NE_/4ouKJW@WE^".charAt(v1) ^ v3.charAt(v1))) {
                            v0 = 1;
                            Toast.makeText(MainActivity.this, "You are not valid user! go out :(", 0).show();
                        }
                        else {
                            ++v1;
                            continue;
                        }

                        break;
                    }

                    if(v0 != 0) {
                        return;
                    }

                    Toast.makeText(MainActivity.this, "You are valid user! Weclome :)", 0).show();
                }
                else {
                    Toast.makeText(MainActivity.this, "You are not valid user! go out :(", 0).show();
                }
            }
        });
    }
}
