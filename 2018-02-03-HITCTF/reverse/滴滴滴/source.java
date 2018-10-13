package com.hitctf.hackandroid;

import android.content.res.AssetFileDescriptor;
import android.media.MediaPlayer$OnPreparedListener;
import android.media.MediaPlayer;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.view.View;
import android.widget.Toast;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.IOException;

public class HITCTF2017 extends AppCompatActivity {
    class LFSR_17 {
        private int[] magic;
        private byte[] register;

        public LFSR_17(HITCTF2017 arg6, String arg7) {
            HITCTF2017.this = arg6;
            super();
            this.register = new byte[]{0, 0, 0, 0};
            this.magic = new int[]{1, 5, 9, 14, 17};
            byte[] v0 = arg7.getBytes();
            this.register[0] = ((byte)((v0[0] & 255) >>> 1 | 12416));
            this.register[1] = ((byte)(v0[0] << 7 | (v0[1] & 255) >>> 1));
            this.register[2] = ((byte)(v0[1] << 7));
        }

        static byte access$100(LFSR_17 arg1) {
            return arg1.next();
        }

        private byte generateFillingBit() {
            int v8 = 16;
            int v7 = 8;
            byte[] v1 = new byte[]{0};
            int v0;
            for(v0 = 0; v0 < this.magic.length; ++v0) {
                if(this.magic[v0] <= v7) {
                    v1[0] = ((byte)(v1[0] ^ (((byte)((this.register[0] & 255) >>> 8 - this.magic[v0] << 7)))));
                }
                else {
                    if(this.magic[v0] > v7 && this.magic[v0] <= v8) {
                        v1[0] = ((byte)(v1[0] ^ (((byte)((this.register[1] & 255) >>> 16 - this.magic[v0] << 7)))));
                        continue;
                    }

                    if(this.magic[v0] > v8 && this.magic[v0] <= 24) {
                        v1[0] = ((byte)(v1[0] ^ (((byte)((this.register[2] & 255) >>> 24 - this.magic[v0] << 7)))));
                        continue;
                    }

                    System.out.println("Wrong magic");
                }

            label_26:
            }

            return v1[0];
        }

        private byte next() {
            int v8 = 2;
            byte[] v2 = new byte[]{0};
            int v1;
            for(v1 = 0; v1 < 8; ++v1) {
                int v0 = this.generateFillingBit();
                v2[0] = ((byte)(v2[0] | (this.register[v8] & 255) >>> 7 - v1));
                this.register[v8] = ((byte)(this.register[1] << 7));
                this.register[1] = ((byte)(this.register[0] << 7 | (this.register[1] & 255) >>> 1));
                this.register[0] = ((byte)(v0 & 255 | (this.register[0] & 255) >>> 1));
            }

            return v2[0];
        }
    }

    class LFSR_25 {
        private int[] magic;
        private byte[] register;

        public LFSR_25(HITCTF2017 arg9, String arg10) {
            HITCTF2017.this = arg9;
            super();
            this.register = new byte[]{0, 0, 0, 0};
            this.magic = new int[]{1, 3, 6, 9, 17, 25};
            byte[] v0 = arg10.getBytes();
            this.register[0] = ((byte)((v0[2] & 255) >>> 1 | 12416));
            this.register[1] = ((byte)(v0[2] << 7 | (v0[3] & 255) >>> 1));
            this.register[2] = ((byte)(v0[3] << 7 | (v0[4] & 255) >>> 1));
            this.register[3] = ((byte)(v0[4] << 7));
        }

        static byte access$000(LFSR_25 arg1) {
            return arg1.next();
        }

        private byte generateFillingBit() {
            int v9 = 24;
            int v8 = 16;
            int v7 = 8;
            byte[] v1 = new byte[]{0};
            int v0;
            for(v0 = 0; v0 < this.magic.length; ++v0) {
                if(this.magic[v0] <= v7) {
                    v1[0] = ((byte)(v1[0] ^ (((byte)((this.register[0] & 255) >>> 8 - this.magic[v0] << 7)))));
                }
                else {
                    if(this.magic[v0] > v7 && this.magic[v0] <= v8) {
                        v1[0] = ((byte)(v1[0] ^ (((byte)((this.register[1] & 255) >>> 16 - this.magic[v0] << 7)))));
                        continue;
                    }

                    if(this.magic[v0] > v8 && this.magic[v0] <= v9) {
                        v1[0] = ((byte)(v1[0] ^ (((byte)((this.register[2] & 255) >>> 24 - this.magic[v0] << 7)))));
                        continue;
                    }

                    if(this.magic[v0] > v9 && this.magic[v0] <= 32) {
                        v1[0] = ((byte)(v1[0] ^ (((byte)((this.register[3] & 255) >>> 32 - this.magic[v0] << 7)))));
                        continue;
                    }

                    System.out.println("Wrong magic");
                }
            }

            return v1[0];
        }

        private byte next() {
            int v9 = 3;
            int v8 = 2;
            byte[] v2 = new byte[]{0};
            int v1;
            for(v1 = 0; v1 < 8; ++v1) {
                int v0 = this.generateFillingBit();
                v2[0] = ((byte)(v2[0] | (this.register[v9] & 255) >>> 7 - v1));
                this.register[v9] = ((byte)(this.register[v8] << 7));
                this.register[v8] = ((byte)(this.register[1] << 7 | (this.register[v8] & 255) >>> 1));
                this.register[1] = ((byte)(this.register[0] << 7 | (this.register[1] & 255) >>> 1));
                this.register[0] = ((byte)(v0 & 255 | (this.register[0] & 255) >>> 1));
            }

            return v2[0];
        }
    }

    private MediaPlayer mp;

    public HITCTF2017() {
        super();
        this.mp = new MediaPlayer();
    }

    public void my_RC4_Test(View arg24) {
        FileInputStream v10;
        String v16 = this.findViewById(2131492951).getText().toString();
        if(v16.length() != 5) {
            Toast.makeText(this, "Key 不合法", 1).show();
            return;
        }

        Toast.makeText(this, "Checking...", 1).show();
        LFSR_17 v11 = new LFSR_17(this, v16);
        LFSR_25 v12 = new LFSR_25(this, v16);
        byte[] v3 = new byte[276524];
        AssetFileDescriptor v6 = this.getResources().openRawResourceFd(2131099648);
        try {
            v10 = v6.createInputStream();
        }
        catch(IOException v5) {
            v5.printStackTrace();
        }

        try {
            FileOutputStream v14 = this.openFileOutput("dec.wav", 0);
            if(v10.read(v3) != -1) {
                Log.i("Encoded", "ENCODEing");
                byte[] v20 = new byte[276524];
                int v9;
                for(v9 = 0; v9 < v3.length; ++v9) {
                    v20[v9] = ((byte)(v3[v9] ^ (((byte)((LFSR_25.access$000(v12) & 255) + (LFSR_17.access$100(v11) & 255))))));
                }

                v14.write(v20);
            }

            v14.close();
            v10.close();
        }
        catch(IOException v5) {
            v5.printStackTrace();
        }

        try {
            MediaPlayer v13 = new MediaPlayer();
            v13.setDataSource(new FileInputStream(new File("/data/data/com.hitctf.hackandroid/files/dec.wav")).getFD());
            v13.setAudioStreamType(3);
            v13.prepareAsync();
            v13.setOnPreparedListener(new MediaPlayer$OnPreparedListener() {
                public void onPrepared(MediaPlayer arg1) {
                    arg1.start();
                }
            });
        }
        catch(Exception v5_1) {
            Log.e("HITCTF2017", v5_1.toString());
            Toast.makeText(this, "Key Wrong!", 1).show();
        }
    }

    protected void onCreate(Bundle arg2) {
        super.onCreate(arg2);
        this.setContentView(2130968602);
    }
}
