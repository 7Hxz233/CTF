package com.tangerine.hitctf.repack;

import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.widget.TextView;
import android.os.Message;
import android.os.Handler;
import android.util.Log;

import java.io.BufferedReader;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.security.Key;
import java.security.spec.AlgorithmParameterSpec;
import javax.crypto.Cipher;
import javax.crypto.SecretKeyFactory;
import javax.crypto.spec.DESKeySpec;
import javax.crypto.spec.IvParameterSpec;
import android.util.Base64;

import java.sql.Time;

public class MainActivity extends AppCompatActivity {

    private TextView tv = null;
    private String flag = null;

    private String[] trans1(String[] s) {
        String[] result = {"0", "0", "0"};

        for (int j = 0; j < s.length; ++j) {
            byte[] tmpByte = s[j].getBytes();
            int[] tmpInt = new int[tmpByte.length];

            for (int i = 0; i < tmpByte.length; ++i) {
                if (tmpByte[i] == 0x2e) {
                    tmpByte[i] = 0x20;
                }
                tmpInt[i] = (tmpByte[i] / 16) + (tmpByte[i]) % 16;
                System.out.println(tmpInt[i]);
            }

            for (int i = 0; i < tmpByte.length / 2; ++i) {
                if (String.valueOf(tmpInt[i * 2]).length() < 2) {
                    result[j] += ("0" + String.valueOf(tmpInt[i * 2]));
                } else {
                    result[j] += String.valueOf(tmpInt[i * 2]);
                }
            }

            for (int i = 0; i < tmpByte.length / 2; ++i) {
                if (String.valueOf(tmpInt[i * 2 + 1]).length() < 2) {
                    result[j] += ("0" + String.valueOf(tmpInt[i * 2 + 1]));
                } else {
                    result[j] += String.valueOf(tmpInt[i * 2 + 1]);
                }
            }
            result[j] = result[j].substring(1, result[j].length());
        }

        return result;
    }

    private String trans2(double[] codeNumber) {

        char[] symbolchars = {'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','$', '_'};
        double[] probability = {0.080,0.013,0.026,0.042,0.127,0.022,0.020,0.060,0.069,0.001,0.007,0.040,0.024,0.067,0.075,0.019,0.001,0.059,0.063,0.090,0.027,0.009,0.023,0.001,0.019,0.001,0.012, 0.003};
        String result = "";

        double symbolRangeLow[] = new double[probability.length];
        for (int i = 0; i < symbolRangeLow.length; i++) {
            symbolRangeLow[i] = 0;
            for (int j = 0; j < i; j++) {
                symbolRangeLow[i] += probability[j];
            }
        }

        for (int j = 0; j < codeNumber.length; ++j) {
            String symbols = "";
            double subSymbolRangeLow[] = new double[symbolRangeLow.length];
            double subSymbolRange[] = new double[probability.length];
            double subRangeLow = 0;
            double subRange = 0;
            for (int i = 0; i < symbolchars.length; i++) {
                subSymbolRangeLow[i] = symbolRangeLow[i];
                subSymbolRange[i] = probability[i];
            }
            int currentSymbol = 0;
            do {
                for (int i = 0; i < symbolchars.length; i++) {
                    if (codeNumber[j] >= subSymbolRangeLow[i]
                            && codeNumber[j] < subSymbolRangeLow[i]
                            + subSymbolRange[i]) {
                        subRangeLow = subSymbolRangeLow[i];
                        subRange = subSymbolRange[i];
                        currentSymbol = i;
                    }
                }
                double subSymbolProbSum = subRangeLow;
                for (int i = 0; i < symbolchars.length; i++) {
                    subSymbolRange[i] = subRange * probability[i];
                    subSymbolRangeLow[i] = subSymbolProbSum;
                    subSymbolProbSum = subSymbolProbSum + subSymbolRange[i];
                }

                symbols += symbolchars[currentSymbol];
            } while (symbolchars[currentSymbol] != '$');
            result += symbols.substring(0, symbols.length() - 1);
        }

        return result;
    }

    private void getAssetsFromApk() {
        try {
            InputStreamReader inputReader = new InputStreamReader(getResources().getAssets().open("input"));
            BufferedReader bufReader = new BufferedReader(inputReader);
            String[] s = bufReader.readLine().split(",");

            String result[] = trans1(s);
            if (!this.getResources().getText(R.string.trust1).equals(result[0]) ||
                !this.getResources().getText(R.string.trust2).equals(result[1]) ||
                !this.getResources().getText(R.string.trust3).equals(result[2])) {
                return;
            }

            double[] doubleResult = new double[s.length];
            for (int i = 0; i < doubleResult.length; ++i) {
                doubleResult[i] = Double.parseDouble(s[i]);
            }
            flag = trans2(doubleResult);
        }
        catch (Exception e) {
            e.printStackTrace();
        }
    }

    private String EncypteFlag(String flag, String key) {
        String result = (String)this.getResources().getText(R.string.NULL);
        try {
            DESKeySpec dks = new DESKeySpec(key.getBytes());
            SecretKeyFactory keyFactory = SecretKeyFactory.getInstance((String)this.getResources().getText(R.string.DES));
            Key secretKey = keyFactory.generateSecret(dks);
            Cipher cipher = Cipher.getInstance((String)this.getResources().getText(R.string.type));
            IvParameterSpec iv = new IvParameterSpec(key.substring(key.length() - 8, key.length()).getBytes());
            AlgorithmParameterSpec parameterSpec = iv;
            cipher.init(Cipher.ENCRYPT_MODE, secretKey, parameterSpec);
            byte[] bytes = cipher.doFinal(flag.getBytes());
            result = Base64.encodeToString(bytes, 0);
        }
        catch (Exception e) {
            e.printStackTrace();
        }
        return result;
    }

    private class setEncyptedFlagThread extends Thread {
        @Override
        public void run() {
            do {
                try {
                    Thread.sleep(1000);
                    Message msg = new Message();
                    msg.what = 1;
                    mHandler.sendMessage(msg);
                }
                catch (InterruptedException e) {
                    e.printStackTrace();
                }
            } while (true);
        }
    }

    private Handler mHandler = new Handler() {
        @Override
        public void handleMessage (Message msg) {
            super.handleMessage(msg);
            switch (msg.what) {
                case 1:
                    long t = System.currentTimeMillis();
                    tv.setText("The encypted flag is:\n" + EncypteFlag(flag, Long.toString(t)));
                    break;

                default:
                    break;

            }
        }
    };

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        tv = (TextView)findViewById(R.id.textView);
        flag = (String)this.getResources().getText(R.string.flag);
        getAssetsFromApk();
        new setEncyptedFlagThread().start();
    }
}
