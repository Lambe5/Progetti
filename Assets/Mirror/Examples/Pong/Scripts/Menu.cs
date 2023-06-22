using Mirror.Examples.Pong;
using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.SceneManagement;

public class Menu : MonoBehaviour
{
    public NetworkManagerGame manager;
    public GameObject menuPanel;

    public void host()
    {
        manager.StartHost();
        menuPanel.SetActive(false);
    }

    public void setIP(string ip)
    {
        manager.networkAddress = ip;
    }

    public void join()
    {
        manager.StartClient();
        menuPanel.SetActive(false);
    }

    public void stop()
    {
        if (manager.mode == Mirror.NetworkManagerMode.Host)
        {
            manager.StopHost();
            SceneManager.LoadScene(SceneManager.GetActiveScene().name);
        }
        else if (manager.mode == Mirror.NetworkManagerMode.ClientOnly)
        {
            manager.StopClient();
            SceneManager.LoadScene(SceneManager.GetActiveScene().name);
        }
    }
}
