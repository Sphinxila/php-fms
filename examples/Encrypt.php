<?php
$path = __DIR__ . "/direct.file";

class MyCrypter extends \PHPFms\Template\CryptEngine {
    /**
     * A bad encryptor
     * @param string $buffer
     * @return bool
     */
    public function encrypt(string &$buffer): bool {
        $buffer = base64_encode($buffer);
        return true;
    }

    /**
     * A bad decryptor
     * @param string $buffer
     * @return bool
     */
    public function decrypt(string &$buffer): bool
    {
        $buffer = base64_decode($buffer);
        return true;
    }
}

$crypt = new MyCrypter();

try {
    $file = new \PHPFms\File($path, "w+");
    $file->setCryptEngine($crypt);
    $file->write("Encrypt\n");
    $file->close();
} catch (\PHPFms\Exception\PathError $error) {
    echo $error->getMessage();
} catch (\PHPFms\Exception\PermissionError $error) {
    echo $error->getMessage();
}
