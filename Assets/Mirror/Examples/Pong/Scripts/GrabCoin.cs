using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using Mirror;
using Mirror.Examples.Pong;

public class GrabCoin : NetworkBehaviour
{
    public bool available = true;
    private void OnCollisionEnter2D(Collision2D collision)
    {
        if(collision.gameObject.name == "Skull(Clone)" || collision.gameObject.name == "Skull(Clone)1")
        {
            ClaimPrize(collision.gameObject);
            if (isClientOnly)
                destroyCmd();
            else destroyRpc();
        }
    }

    [Command(requiresAuthority = false)]
    void destroyCmd()
    {
        if (gameObject != null)
            NetworkServer.Destroy(gameObject);
    }

    [ClientRpc]
    void destroyRpc()
    {
        if (gameObject != null)
            NetworkServer.Destroy(gameObject);
    }

    public void ClaimPrize(GameObject player)
    {
        if (available)
        {
            // This is a fast switch to prevent two players claiming the prize in a bang-bang close contest for it.
            // First hit turns it off, pending the object being destroyed a few frames later.
            available = false;
            int points = 1;

            // award the points via SyncVar on the PlayerController
            player.GetComponent<Player>().score += points;
        }
    }
}
