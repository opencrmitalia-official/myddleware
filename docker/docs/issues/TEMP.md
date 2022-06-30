Missing rowCount method after upgrade Doctrine to 3.3.x [Mydd3]

Hi!

After the realease of Myddle 3.1.0 you push Doctrine from 2.13.7 to 3.3.3

The commit
https://github.com/Myddleware/myddleware/commit/b1b61f282db535feb823f8430dd6143de4d35413#r77370359

But this break the JobScheduler because the usage of the method rowCount at the following lines
- https://github.com/Myddleware/myddleware/blob/7601bf750f14f6b6c23a6ea67d59c3b71fe38ab7/src/Manager/JobManager.php#L791
-


This is the original DBAL file
- for 2 (with method) https://github.com/doctrine/dbal/blob/c2b8e6e82732a64ecde1cddf9e1e06cb8556e3d8/lib/Doctrine/DBAL/Statement.php#L262
- for 3 (withoud method) https://github.com/doctrine/dbal/blob/9e0facb905f71a4495fd32f8e56fd14c4c7077df/src/Statement.php#L215

