import React from "react";

import {
  Form,
  Input,
  Radio,
  RadioChangeEvent,
  Space,
  Switch,
  Button,
} from "antd";
import MarkdownEditor from "@uiw/react-markdown-editor";
import axioas from "axios";

interface Data {
  browse?: string;
  create_time?: string;
  delete_time?: string | null;
  id?: string;
  is_release?: string;
  password?: string | null;
  subtitle?: string;
  title?: string;
  update_time?: string;
}

interface State {
  password: number;
  passwordInput: string;
  data: Data;
}
const { TextArea } = Input;

export default class Edit extends React.Component {
  public state: State = {
    password: 0,
    passwordInput: "none",
    data: {},
  };

  constructor(props: {}) {
    super(props);
    this.passwordChange = this.passwordChange.bind(this);
    // this.info();
    console.log(10);
  }

  componentDidMount() {
    this.info();
  }

  async info() {
    const res = await axioas.get("/article/6");
    const data = res.data;
    this.setState({ data: data });
    this.setState({ password: data.password == null ? 0 : 1 });
    console.log(this.state);
    console.log(100);
  }

  passwordChange(e: RadioChangeEvent) {
    this.setState({ passwordInput: e.target.value == 1 ? "block" : "none" });
  }

  render() {
    return (
      <Form labelCol={{ span: 2 }}>
        <Form.Item label="标题">
          <Input value={this.state.data.title} />
        </Form.Item>
        <Form.Item label="副标题">
          {/* <TextArea rows={4} /> */}
          <Input value={this.state.data.subtitle} />
        </Form.Item>
        <Form.Item label="设置密码">
          <Space>
            <Radio.Group
              defaultValue={this.state.password}
              onChange={this.passwordChange}
            >
              <Radio.Button value={0}>无密码</Radio.Button>
              <Radio.Button value={1}>密码</Radio.Button>
            </Radio.Group>
            <Input style={{ display: this.state.passwordInput }} />
          </Space>
        </Form.Item>
        <Form.Item label="显示">
          <Switch
            checkedChildren="开启"
            unCheckedChildren="关闭"
            defaultChecked
          />
        </Form.Item>
        <Form.Item label="详情">
          <>
            <MarkdownEditor value="123165465" style={{ height: "700px" }} />
          </>
        </Form.Item>
        <Form.Item wrapperCol={{ span: 12, offset: 2 }}>
          <Button type="text">保存</Button>
        </Form.Item>
      </Form>
    );
  }
}
