using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class AudioManager : MonoBehaviour
{
    public GameObject playAudio;
    public GameObject stopAudio;

    public void StopAllAudio()
    {
        AudioListener.volume = 0;
        stopAudio.SetActive(false);
        playAudio.SetActive(true);
    }

    public void PlayAllAudio()
    {
        AudioListener.volume = 1;
        stopAudio.SetActive(true);
        playAudio.SetActive(false);
    }
}
