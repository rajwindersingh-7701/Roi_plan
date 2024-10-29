/** CONFIGURATIONS **/



const URL = "https://minting.moonbyeol.com/";



let splited_uri = URL.split(".");



let splited_uri2 = splited_uri[0].split("//");



const COMPANY = splited_uri2[1].toUpperCase();



const Web3Modal = window.Web3Modal.default;



const evmChains = window.evmChains;



const CHAIN_ID = 97; // 56 : bsc-mainnet(0x38) 97 : bsc-testnet(0x61) 



const TOKEN_ADDRESS = (CHAIN_ID==56)?"0x9a558f2ce1ad9f1f63df0cc05b97799041b03d8e":"0x80b2fe93fe606629bbb184d10638bded1e4880d0";



const CONTRACT_ADDRESS = (CHAIN_ID==56)?"":""; 



const TOKEN_ABI = (CHAIN_ID==56)?token_abi_mainnet:token_abi_testnet;



const CONTRACT_ABI = (CHAIN_ID==56)?contract_abi_mainnet:contract_abi_testnet;



const GAS_FEES = "1";



const GAS_LIMIT = "3000000";



const TOKEN = (CHAIN_ID==56)?"Byeol":"PUBG";



const BSCSCAN = (CHAIN_ID==56)?"https://bscscan.com/":"https://testnet.bscscan.com/";



const RPC = (CHAIN_ID==56)?"https://bsc-dataseed1.binance.org:443":"https://data-seed-prebsc-1-s1.binance.org:8545";





